<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalOperasional;
use App\Models\Layanan;
use App\Services\OpenRouterChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    public function __construct(
        private readonly OpenRouterChatService $openRouterChatService,
    ) {
    }

    public function message(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:4000',
            'history' => 'nullable|array|max:20',
            'history.*.role' => 'required_with:history|in:user,assistant,system',
            'history.*.content' => 'required_with:history|string|max:4000',
        ]);

        $messages = [
            [
                'role' => 'system',
                'content' => $this->buildSystemPrompt($request),
            ],
        ];

        foreach ($validated['history'] ?? [] as $historyMessage) {
            $messages[] = [
                'role' => $historyMessage['role'],
                'content' => $historyMessage['content'],
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $validated['message'],
        ];

        try {
            $result = $this->openRouterChatService->chat($messages);

            return response()->json([
                'status' => true,
                'message' => 'Balasan chatbot berhasil dibuat.',
                'data' => [
                    'reply' => $result['content'],
                    'model' => $result['model'],
                    'id' => $result['id'],
                    'usage' => $result['usage'],
                ],
            ]);
        } catch (\Throwable $exception) {
            Log::error('Chatbot request failed', [
                'message' => $exception->getMessage(),
                'user_id' => $request->user()?->id,
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Gagal memproses permintaan chatbot.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    private function buildSystemPrompt(Request $request): string
    {
        $today = now()->toDateString();
        $jadwalList = JadwalOperasional::query()
            ->orderBy('id')
            ->get(['jam_buka', 'jam_tutup', 'status', 'keterangan']);

        $layanans = Layanan::query()
            ->with([
                'kategori:id,name',
                'promo' => fn ($query) => $query
                    ->whereDate('tanggal_mulai', '<=', $today)
                    ->whereDate('tanggal_selesai', '>=', $today)
                    ->orderByDesc('diskon_persen'),
            ])
            ->orderBy('name')
            ->get(['id', 'kategori_id', 'name', 'deskripsi', 'harga', 'estimasi_menit']);

        $jadwalText = $jadwalList->isEmpty()
            ? 'Jadwal operasional belum tersedia.'
            : $jadwalList->map(function ($jadwal) {
                $detail = "{$jadwal->status}: {$jadwal->jam_buka} - {$jadwal->jam_tutup}";

                if ($jadwal->keterangan) {
                    $detail .= " ({$jadwal->keterangan})";
                }

                return $detail;
            })->implode('; ');

        $layananText = $layanans->isEmpty()
            ? 'Belum ada data layanan.'
            : $layanans->map(function ($layanan) {
                $promoAktif = $layanan->promo->first();
                $kategori = $layanan->kategori?->name ?? 'Tanpa kategori';
                $detail = "{$layanan->name} | kategori: {$kategori} | harga: Rp ".number_format((float) $layanan->harga, 0, ',', '.')." | estimasi: {$layanan->estimasi_menit} menit";

                if ($promoAktif) {
                    $hargaPromo = (float) $layanan->harga * (1 - ($promoAktif->diskon_persen / 100));
                    $detail .= " | promo aktif: {$promoAktif->diskon_persen}% (Rp ".number_format($hargaPromo, 0, ',', '.').")";
                }

                if ($layanan->deskripsi) {
                    $detail .= " | deskripsi: ".trim($layanan->deskripsi);
                }

                return $detail;
            })->implode("\n");

        $userName = $request->user()?->name ?? 'Pelanggan';

        return <<<PROMPT
Anda adalah asisten virtual Camela.
Jawab selalu dalam Bahasa Indonesia yang ramah, singkat, jelas, dan membantu.
Fokus Anda adalah membantu pelanggan memahami layanan, promo, jadwal operasional, dan proses booking di Camela.
Jangan mengarang informasi di luar konteks yang diberikan. Jika data tidak tersedia, katakan dengan jujur bahwa informasinya belum tersedia dan sarankan menghubungi admin.
Jika pengguna menanyakan harga, gunakan angka dari konteks ini.
Jika pengguna menanyakan promo, hanya sebut promo yang aktif.
Nama pengguna saat ini: {$userName}
Tanggal hari ini: {$today}

Jadwal operasional:
{$jadwalText}

Daftar layanan:
{$layananText}
PROMPT;
    }
}
