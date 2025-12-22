<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Midtrans\CoreApi;
use Midtrans\Config;
use Carbon\Carbon;
use Kreait\Firebase\Factory;

class BookingController extends Controller
{
    private $firebaseDatabase;

    public function __construct()
    {
        $this->firebaseDatabase = (new Factory)
            ->withServiceAccount(config('firebase.firebase.service_account'))
            ->withDatabaseUri('https://fre-kantin-default-rtdb.firebaseio.com')
            ->createDatabase();

        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
        Config::$isProduction = env('MIDTRANS_ENV') === 'production';
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    private function generateOrderId()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        do {
            $randomString = 'BOOKING-';
            for ($i = 0; $i < 7; $i++) {
                $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
            }
            $existingOrder = Booking::where('order_id', $randomString)->exists();
        } while ($existingOrder);

        return $randomString;
    }

    /**
     * Create booking from cart
     */
    public function createBooking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_booking' => 'required|date_format:H:i',
            'jenis_pembayaran' => 'required|in:dp,lunas',
            'items' => 'required|array|min:1',
            'items.*.layanan_id' => 'required|exists:layanans,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
            'payment_type' => 'required|in:BANK_TRANSFER,QRIS,GOPAY',
            'bank' => 'required_if:payment_type,BANK_TRANSFER|in:bri,bni,bca,mandiri,permata'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $paymentType = $request->payment_type;
        $bank = $request->bank;

        // Validasi bank untuk BANK_TRANSFER
        if ($paymentType === 'BANK_TRANSFER' && !$bank) {
            return response()->json([
                'status' => false,
                'message' => 'Bank is required for bank transfer payment'
            ], 400);
        }

        DB::beginTransaction();
        try {
            // Calculate total
            $totalHarga = 0;
            foreach ($request->items as $item) {
                $totalHarga += $item['harga'] * $item['qty'];
            }

            // Calculate payment amount based on type
            $totalPembayaran = $request->jenis_pembayaran === 'dp'
                ? $totalHarga * 0.5  // DP 50%
                : $totalHarga;       // Lunas 100%

            $orderId = $this->generateOrderId();

            // Create Booking
            $booking = Booking::create([
                'order_id' => $orderId,
                'user_id' => $user->id,
                'tanggal_booking' => $request->tanggal_booking,
                'jam_booking' => $request->jam_booking,
                'status' => 'pending',
                'total_harga' => $totalHarga,
                'jenis_pembayaran' => $request->jenis_pembayaran,
                'total_pembayaran' => $totalPembayaran,
                'payment_type' => $paymentType,
            ]);

            // Create Booking Details
            foreach ($request->items as $item) {
                BookingDetail::create([
                    'booking_id' => $booking->id,
                    'layanan_id' => $item['layanan_id'],
                    'harga' => $item['harga'],
                    'qty' => $item['qty'],
                ]);
            }

            // Proses pembayaran menggunakan CoreApi
            $paymentGatewayResponse = $this->processPayment(
                $paymentType,
                $totalPembayaran,
                $orderId,
                $bank,
                $user,
                $request->items
            );

            if (isset($paymentGatewayResponse['error'])) {
                DB::rollBack();
                throw new \Exception($paymentGatewayResponse['error']);
            }

            // Create payment record
            $pembayaran = Pembayaran::create([
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'gross_amount' => $totalPembayaran,
                'transaction_status' => 'pending',
                'payment_type' => $paymentType,
                'payment_gateway' => 'midtrans',
                'payment_gateway_reference_id' => $orderId,
                'payment_gateway_response' => json_encode($paymentGatewayResponse['response']),
                'payment_date' => Carbon::now(),
                'expired_at' => Carbon::now()->addHours(1),
                'bank' => $paymentGatewayResponse['va_bank'],
                'va_number' => $paymentGatewayResponse['va_number'],
                'qr_url' => $paymentGatewayResponse['qr_string'],
                'deeplink_url' => $paymentGatewayResponse['deeplink_redirect'],
            ]);

            // Push notification to Firebase
            $this->firebaseDatabase
                ->getReference('notifications/bookings')
                ->push([
                    'order_id' => $orderId,
                    'booking_id' => $booking->id,
                    'user_name' => $user->name,
                    'total_amount' => $totalPembayaran,
                    'status' => 'pending',
                    'timestamp' => Carbon::now()->timestamp,
                ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Booking created successfully',
                'data' => [
                    'booking_id' => $booking->id,
                    'order_id' => $orderId,
                    'total_harga' => $totalHarga,
                    'total_pembayaran' => $totalPembayaran,
                    'jenis_pembayaran' => $request->jenis_pembayaran,
                    'booking' => $booking,
                    'payment' => $pembayaran
                ]
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Booking creation failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Error creating booking or payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function processPayment($paymentType, $totalAmount, $orderId, $bank = null, $user, $items)
    {
        // Log untuk debugging
        Log::info('Midtrans Config Check', [
            'server_key_exists' => !empty(env('MIDTRANS_SERVER_KEY')),
            'server_key_length' => strlen(env('MIDTRANS_SERVER_KEY')),
            'is_production' => env('MIDTRANS_ENV') === 'production'
        ]);

        $transaction_details = [
            'order_id' => $orderId,
            'gross_amount' => $totalAmount,
        ];

        $item_details = [];
        foreach ($items as $item) {
            $item_details[] = [
                'id' => $item['layanan_id'],
                'price' => $item['harga'],
                'quantity' => $item['qty'],
                'name' => 'Layanan ID ' . $item['layanan_id'],
            ];
        }

        $customer_details = [
            'first_name' => $user->name,
            'email' => $user->email,
            'phone' => $user->no_telepon ?? $user->phone ?? 'N/A',
        ];

        // Add custom expiry
        $custom_expiry = [
            'expiry_duration' => 1,
            'unit' => 'hour',
        ];

        // Base transaction data
        $transaction_data = [
            'transaction_details' => $transaction_details,
            'item_details' => $item_details,
            'customer_details' => $customer_details,
            'custom_expiry' => $custom_expiry,
        ];

        // Set payment method based on type
        switch ($paymentType) {
            case 'BANK_TRANSFER':
                $transaction_data['payment_type'] = 'bank_transfer';
                $transaction_data['bank_transfer'] = [
                    'bank' => strtolower($bank)
                ];
                break;

            case 'QRIS':
                $transaction_data['payment_type'] = 'qris';
                break;

            case 'GOPAY':
                $transaction_data['payment_type'] = 'gopay';
                break;

            default:
                $transaction_data['payment_type'] = 'bank_transfer';
                break;
        }

        try {
            $response = CoreApi::charge($transaction_data);

            $result = [
                'response' => $response,
                'va_bank' => null,
                'va_number' => null,
                'redirect_url' => null,
                'qr_string' => null,
                'deeplink_redirect' => null
            ];

            // Handle different payment types response
            if ($response->payment_type === 'bank_transfer') {
                if (isset($response->va_numbers) && !empty($response->va_numbers)) {
                    $result['va_bank'] = $response->va_numbers[0]->bank;
                    $result['va_number'] = $response->va_numbers[0]->va_number;
                } elseif (isset($response->permata_va_number)) {
                    $result['va_bank'] = 'permata';
                    $result['va_number'] = $response->permata_va_number;
                }
            } elseif ($response->payment_type === 'qris') {
                if (isset($response->actions)) {
                    foreach ($response->actions as $action) {
                        if ($action->name === 'generate-qr-code') {
                            $result['qr_string'] = $action->url;
                            break;
                        }
                    }
                }
            } elseif ($response->payment_type === 'gopay') {
                if (isset($response->actions)) {
                    foreach ($response->actions as $action) {
                        if ($action->name === 'generate-qr-code') {
                            $result['qr_string'] = $action->url;
                        } elseif ($action->name === 'deeplink-redirect') {
                            $result['deeplink_redirect'] = $action->url;
                        }
                    }
                }
            }

            return $result;
        } catch (\Exception $e) {
            Log::error('Midtrans payment processing failed: ' . $e->getMessage());
            return ['error' => 'Payment processing failed: ' . $e->getMessage()];
        }
    }

    public function history()
{
    try {
        $bookings = Booking::with([
                'details.layanan',
                'pembayaran'
            ])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        $formatted = $bookings->map(function ($booking) {
            $payment = $booking->pembayaran;

            return [
                'booking_id' => $booking->id,
                'order_id' => $booking->order_id,
                'total_harga' => (float) $booking->total_harga,
                'total_pembayaran' => (float) $booking->total_pembayaran,
                'jenis_pembayaran' => $booking->jenis_pembayaran,

                // ===== BOOKING =====
                'booking' => [
                    'order_id' => $booking->order_id,
                    'user_id' => $booking->user_id,
                    'tanggal_booking' => $booking->tanggal_booking,
                    'jam_booking' => $booking->jam_booking,
                    'status' => $booking->status,
                    'total_harga' => (float) $booking->total_harga,
                    'jenis_pembayaran' => $booking->jenis_pembayaran,
                    'total_pembayaran' => (float) $booking->total_pembayaran,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at,
                    'id' => $booking->id,
                ],

                // ===== DETAILS + LAYANAN =====
                'details' => $booking->details->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'harga' => (float) $detail->harga,
                        'qty' => $detail->qty,
                        'subtotal' => (float) $detail->subtotal,

                        'layanan' => $detail->layanan ? [
                            'id' => $detail->layanan->id,
                            'nama' => $detail->layanan->nama,
                            'deskripsi' => $detail->layanan->deskripsi,
                            'harga' => (float) $detail->layanan->harga,
                            'estimasi_menit' => $detail->layanan->estimasi_menit,
                            'image' => $detail->layanan->image,
                        ] : null,
                    ];
                }),

                // ===== PAYMENT =====
                'payment' => $payment ? [
                    'booking_id' => $payment->booking_id,
                    'order_id' => $payment->order_id,
                    'gross_amount' => (float) $payment->gross_amount,
                    'transaction_status' => $payment->transaction_status,
                    'payment_type' => $payment->payment_type,
                    'payment_gateway' => $payment->payment_gateway,
                    'payment_gateway_reference_id' => $payment->payment_gateway_reference_id,
                    'payment_date' => $payment->payment_date,
                    'expired_at' => $payment->expired_at,
                    'bank' => $payment->bank,
                    'va_number' => $payment->va_number,
                    'qr_url' => $payment->qr_url,
                    'deeplink_url' => $payment->deeplink_url,
                    'created_at' => $payment->created_at,
                    'updated_at' => $payment->updated_at,
                    'id' => $payment->id,
                    'is_paid' => $payment->is_paid ?? false,
                    'is_expired' => $payment->is_expired ?? false,
                    'status_label' => $payment->status_label ?? 'Menunggu Pembayaran',
                ] : null,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Booking history fetched successfully',
            'data' => $formatted
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Failed to get history: ' . $e->getMessage(),
        ], 500);
    }
}



    /**
     * Get booking detail
     */
    public function show($id)
    {
        try {
            $booking = Booking::with(['details.layanan', 'pembayaran'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'data' => $booking
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Booking not found',
            ], 404);
        }
    }

    /**
     * Check payment status
     */
    public function checkPaymentStatus($bookingId)
    {
        try {
            $booking = Booking::where('user_id', Auth::id())
                ->findOrFail($bookingId);

            $pembayaran = $booking->pembayaran;

            if (!$pembayaran) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment data not found',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'order_id' => $pembayaran->order_id,
                    'transaction_status' => $pembayaran->transaction_status,
                    'booking_status' => $booking->status,
                    'is_paid' => in_array($pembayaran->transaction_status, ['settlement', 'capture', 'success']),
                    'payment' => $pembayaran,
                    'booking' => $booking
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Midtrans callback webhook
     */
    public function callback(Request $request)
    {
        try {
            // Ambil raw input dari request
            $serverKey = env('MIDTRANS_SERVER_KEY');
            $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

            // Verifikasi signature untuk keamanan
            if ($hashed !== $request->signature_key) {
                Log::warning('Invalid signature key for booking callback', [
                    'order_id' => $request->order_id,
                    'signature_received' => $request->signature_key,
                    'signature_calculated' => $hashed
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Invalid signature'
                ], 400);
            }

            // Cari booking berdasarkan order_id
            $booking = Booking::where('order_id', $request->order_id)->first();

            if (!$booking) {
                Log::error('Booking not found for callback', ['order_id' => $request->order_id]);
                return response()->json([
                    'status' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            // Ambil payment record terkait
            $pembayaran = $booking->pembayaran;

            if (!$pembayaran) {
                Log::error('Payment record not found for booking', ['order_id' => $request->order_id]);
                return response()->json([
                    'status' => false,
                    'message' => 'Payment record not found'
                ], 404);
            }

            // Tentukan status berdasarkan transaction_status dari Midtrans
            $transactionStatus = $request->transaction_status;
            $fraudStatus = $request->fraud_status ?? null;

            Log::info('Booking callback received', [
                'order_id' => $request->order_id,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'payment_type' => $request->payment_type,
                'gross_amount' => $request->gross_amount
            ]);

            // Update status berdasarkan transaction_status
            switch ($transactionStatus) {
                case 'capture':
                    if ($fraudStatus == 'challenge') {
                        $this->updateBookingStatus($booking, $pembayaran, 'challenge', $request);
                    } else if ($fraudStatus == 'accept') {
                        $this->updateBookingStatus($booking, $pembayaran, 'success', $request);
                    }
                    break;

                case 'settlement':
                    $this->updateBookingStatus($booking, $pembayaran, 'success', $request);
                    break;

                case 'pending':
                    $this->updateBookingStatus($booking, $pembayaran, 'pending', $request);
                    break;

                case 'deny':
                    $this->updateBookingStatus($booking, $pembayaran, 'failed', $request);
                    break;

                case 'expire':
                    $this->updateBookingStatus($booking, $pembayaran, 'expired', $request);
                    break;

                case 'cancel':
                    $this->updateBookingStatus($booking, $pembayaran, 'cancelled', $request);
                    break;

                case 'refund':
                    $this->updateBookingStatus($booking, $pembayaran, 'refunded', $request);
                    break;

                case 'partial_refund':
                    $this->updateBookingStatus($booking, $pembayaran, 'partial_refunded', $request);
                    break;

                case 'failure':
                    $this->updateBookingStatus($booking, $pembayaran, 'failed', $request);
                    break;

                default:
                    Log::warning('Unknown transaction status for booking', [
                        'order_id' => $request->order_id,
                        'transaction_status' => $transactionStatus
                    ]);
                    break;
            }

            return response()->json([
                'status' => true,
                'message' => 'Callback processed successfully'
            ], 200);

        } catch (\Exception $e) {
            Log::error('Booking callback processing failed: ' . $e->getMessage(), [
                'order_id' => $request->order_id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Callback processing failed'
            ], 500);
        }
    }

    private function updateBookingStatus($booking, $pembayaran, $status, $request)
    {
        try {
            // Mapping status untuk booking
            $bookingStatusMap = [
                'success' => 'confirmed',
                'settlement' => 'confirmed',
                'capture' => 'confirmed',
                'pending' => 'pending',
                'challenge' => 'pending',
                'failed' => 'cancelled',
                'expired' => 'cancelled',
                'cancelled' => 'cancelled',
                'refunded' => 'refunded',
                'partial_refunded' => 'partial_refunded',
            ];

            $bookingStatus = $bookingStatusMap[$status] ?? 'pending';

            // Update booking status
            $booking->update([
                'status' => $bookingStatus,
                'updated_at' => Carbon::now()
            ]);

            // Update payment record
            $paymentUpdateData = [
                'transaction_status' => $status,
                'payment_gateway_response' => json_encode($request->all()),
                'transaction_id' => $request->transaction_id ?? null,
                'fraud_status' => $request->fraud_status ?? null,
                'updated_at' => Carbon::now()
            ];

            // Jika status success, update settlement_time
            if ($status === 'success') {
                $paymentUpdateData['settlement_time'] = $request->settlement_time ?? Carbon::now();
            }

            // Update transaction_time if available
            if (isset($request->transaction_time)) {
                $paymentUpdateData['transaction_time'] = $request->transaction_time;
            }

            $pembayaran->update($paymentUpdateData);

            // Update Firebase
            $this->updateFirebaseBooking($booking->order_id, $bookingStatus, $booking->total_pembayaran);

            // Jika pembayaran berhasil, kirim notifikasi atau email (opsional)
            if ($status === 'success') {
                $this->handleSuccessfulPayment($booking);
            }

            Log::info('Booking status updated successfully', [
                'order_id' => $booking->order_id,
                'booking_status' => $bookingStatus,
                'payment_status' => $status,
                'amount' => $booking->total_pembayaran
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to update booking status: ' . $e->getMessage(), [
                'order_id' => $booking->order_id,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function updateFirebaseBooking($orderId, $status, $amount)
    {
        try {
            // Cari reference berdasarkan order_id di notifications/bookings
            $bookingsRef = $this->firebaseDatabase->getReference('notifications/bookings');
            $snapshot = $bookingsRef->orderByChild('order_id')->equalTo($orderId)->getSnapshot();

            if ($snapshot->exists()) {
                foreach ($snapshot->getValue() as $key => $value) {
                    // Update record yang sudah ada di notifications/bookings
                    $this->firebaseDatabase
                        ->getReference('notifications/bookings/' . $key)
                        ->update([
                            'status' => $status,
                            'updated_at' => Carbon::now()->timestamp,
                            'payment_date' => $status === 'confirmed' ? Carbon::now()->timestamp : null
                        ]);

                    Log::info('Firebase notification/bookings updated', [
                        'firebase_key' => $key,
                        'order_id' => $orderId,
                        'status' => $status
                    ]);

                    break; // Hanya update yang pertama ditemukan
                }
            } else {
                Log::warning('Firebase booking record not found', [
                    'order_id' => $orderId,
                    'searched_in' => 'notifications/bookings'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Failed to update Firebase booking: ' . $e->getMessage(), [
                'order_id' => $orderId,
                'status' => $status,
                'error' => $e->getMessage()
            ]);
            // Jangan throw exception di sini agar tidak mengganggu proses utama
        }
    }

    private function handleSuccessfulPayment($booking)
    {
        try {


            Log::info('Booking payment successful', [
                'order_id' => $booking->order_id,
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
                'amount' => $booking->total_pembayaran,
                'jenis_pembayaran' => $booking->jenis_pembayaran
            ]);



        } catch (\Exception $e) {
            Log::error('Failed to handle successful booking payment: ' . $e->getMessage(), [
                'order_id' => $booking->order_id
            ]);
        }
    }

    /**
     * Method untuk mendapatkan status booking (opsional, untuk debugging)
     */
    public function checkStatus($orderId)
    {
        try {
            $booking = Booking::where('order_id', $orderId)
                ->with(['pembayaran', 'details.layanan'])
                ->first();

            if (!$booking) {
                return response()->json([
                    'status' => false,
                    'message' => 'Booking not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'data' => [
                    'order_id' => $booking->order_id,
                    'booking_id' => $booking->id,
                    'total_harga' => $booking->total_harga,
                    'total_pembayaran' => $booking->total_pembayaran,
                    'jenis_pembayaran' => $booking->jenis_pembayaran,
                    'status' => $booking->status,
                    'payment' => $booking->pembayaran,
                    'details' => $booking->details,
                    'created_at' => $booking->created_at,
                    'updated_at' => $booking->updated_at
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error checking booking status: ' . $e->getMessage()
            ], 500);
        }
    }
}