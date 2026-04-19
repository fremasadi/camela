<?php

namespace Tests\Feature;

use App\Models\JadwalOperasional;
use App\Models\KategoriLayanan;
use App\Models\Layanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChatbotApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_get_chatbot_reply(): void
    {
        config([
            'services.openrouter.api_key' => 'test-openrouter-key',
            'services.openrouter.model' => 'meta-llama/llama-3.1-8b-instruct:free',
            'services.openrouter.base_url' => 'https://openrouter.ai/api/v1',
        ]);

        $user = User::factory()->create([
            'role' => 'customer',
            'no_telp' => '08123456789',
        ]);

        $kategori = KategoriLayanan::create([
            'name' => 'Hair Treatment',
        ]);

        Layanan::create([
            'kategori_id' => $kategori->id,
            'name' => 'Creambath',
            'deskripsi' => 'Perawatan rambut dan relaksasi kulit kepala.',
            'harga' => 75000,
            'image' => [],
            'estimasi_menit' => 60,
        ]);

        JadwalOperasional::create([
            'jam_buka' => '09:00:00',
            'jam_tutup' => '18:00:00',
            'status' => 'buka',
            'keterangan' => 'Senin sampai Sabtu',
        ]);

        Http::fake([
            'https://openrouter.ai/api/v1/chat/completions' => Http::response([
                'id' => 'chatcmpl-test',
                'model' => 'meta-llama/llama-3.1-8b-instruct:free',
                'choices' => [
                    [
                        'message' => [
                            'role' => 'assistant',
                            'content' => 'Layanan creambath tersedia dengan harga Rp 75.000.',
                        ],
                    ],
                ],
                'usage' => [
                    'prompt_tokens' => 100,
                    'completion_tokens' => 12,
                    'total_tokens' => 112,
                ],
            ], 200),
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/chatbot/message', [
            'message' => 'Creambath ada berapa harganya?',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'status' => true,
                'data' => [
                    'reply' => 'Layanan creambath tersedia dengan harga Rp 75.000.',
                    'model' => 'meta-llama/llama-3.1-8b-instruct:free',
                ],
            ]);

        Http::assertSent(function ($request) {
            return $request->url() === 'https://openrouter.ai/api/v1/chat/completions'
                && $request['model'] === 'meta-llama/llama-3.1-8b-instruct:free'
                && $request['messages'][0]['role'] === 'system'
                && str_contains($request['messages'][0]['content'], 'Creambath')
                && $request['messages'][1]['content'] === 'Creambath ada berapa harganya?';
        });
    }
}
