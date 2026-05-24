<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPoint;
use App\Models\UserVoucher;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoucherController extends Controller
{
    public function points(Request $request)
    {
        return response()->json([
            'status' => true,
            'message' => 'Saldo point berhasil diambil.',
            'data' => [
                'balance' => $this->pointBalance($request->user()->id),
            ],
        ]);
    }

    public function pointHistory(Request $request)
    {
        $history = UserPoint::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get(['id', 'booking_id', 'type', 'points', 'description', 'created_at']);

        return response()->json([
            'status' => true,
            'message' => 'Riwayat point berhasil diambil.',
            'data' => $history,
        ]);
    }

    public function vouchers(Request $request)
    {
        $balance = $this->pointBalance($request->user()->id);

        $vouchers = Voucher::query()
            ->where('status', 'active')
            ->orderBy('required_points')
            ->get()
            ->map(fn (Voucher $voucher) => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'name' => $voucher->name,
                'required_points' => $voucher->required_points,
                'discount_type' => $voucher->discount_type,
                'discount_value' => (float) $voucher->discount_value,
                'max_discount' => $voucher->max_discount ? (float) $voucher->max_discount : null,
                'min_transaction' => $voucher->min_transaction ? (float) $voucher->min_transaction : null,
                'expired_days' => $voucher->expired_days,
                'can_redeem' => $balance >= $voucher->required_points,
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Daftar voucher berhasil diambil.',
            'data' => [
                'point_balance' => $balance,
                'vouchers' => $vouchers,
            ],
        ]);
    }

    public function myVouchers(Request $request)
    {
        $this->expireUserVouchers($request->user()->id);

        $vouchers = UserVoucher::query()
            ->with('voucher:id,name')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn (UserVoucher $userVoucher) => [
                'id' => $userVoucher->id,
                'voucher_id' => $userVoucher->voucher_id,
                'name' => $userVoucher->voucher?->name,
                'code' => $userVoucher->code,
                'status' => $userVoucher->status,
                'discount_type' => $userVoucher->discount_type,
                'discount_value' => (float) $userVoucher->discount_value,
                'max_discount' => $userVoucher->max_discount ? (float) $userVoucher->max_discount : null,
                'min_transaction' => $userVoucher->min_transaction ? (float) $userVoucher->min_transaction : null,
                'expired_at' => $userVoucher->expired_at,
                'used_at' => $userVoucher->used_at,
            ]);

        return response()->json([
            'status' => true,
            'message' => 'Voucher saya berhasil diambil.',
            'data' => $vouchers,
        ]);
    }

    public function redeem(Request $request, Voucher $voucher)
    {
        if ($voucher->status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'Voucher tidak aktif.',
            ], 422);
        }

        $userVoucher = DB::transaction(function () use ($request, $voucher) {
            $user = $request->user();

            DB::table('users')
                ->where('id', $user->id)
                ->lockForUpdate()
                ->first();

            $balance = $this->pointBalance($user->id);

            if ($balance < $voucher->required_points) {
                return null;
            }

            UserPoint::create([
                'user_id' => $user->id,
                'type' => 'redeem',
                'points' => -1 * (int) $voucher->required_points,
                'description' => 'Redeem voucher '.$voucher->name,
            ]);

            return UserVoucher::create([
                'user_id' => $user->id,
                'voucher_id' => $voucher->id,
                'code' => $this->generateUserVoucherCode($voucher->code),
                'status' => 'available',
                'discount_type' => $voucher->discount_type,
                'discount_value' => $voucher->discount_value,
                'max_discount' => $voucher->max_discount,
                'min_transaction' => $voucher->min_transaction,
                'expired_at' => now()->addDays($voucher->expired_days),
            ]);
        });

        if (!$userVoucher) {
            return response()->json([
                'status' => false,
                'message' => 'Point tidak mencukupi untuk redeem voucher ini.',
            ], 422);
        }

        return response()->json([
            'status' => true,
            'message' => 'Voucher berhasil diredeem.',
            'data' => $userVoucher,
        ], 201);
    }

    private function pointBalance(int $userId): int
    {
        return (int) UserPoint::query()
            ->where('user_id', $userId)
            ->sum('points');
    }

    private function expireUserVouchers(int $userId): void
    {
        UserVoucher::query()
            ->where('user_id', $userId)
            ->where('status', 'available')
            ->whereNotNull('expired_at')
            ->where('expired_at', '<', now())
            ->update(['status' => 'expired']);
    }

    private function generateUserVoucherCode(string $prefix): string
    {
        do {
            $code = strtoupper($prefix.'-'.Str::random(8));
        } while (UserVoucher::where('code', $code)->exists());

        return $code;
    }
}
