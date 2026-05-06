# Chatbot API

Dokumentasi singkat untuk integrasi frontend ke endpoint chatbot Camela.

## Endpoint

- Method: `POST`
- URL: `/api/chatbot/message`
- Auth: `Bearer Token` (`auth:sanctum`)

## Headers

```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

## Request Body

```json
{
  "message": "Halo, ada promo creambath hari ini?",
  "history": [
    {
      "role": "user",
      "content": "Saya mau tanya layanan di Camela"
    },
    {
      "role": "assistant",
      "content": "Tentu, silakan tanyakan layanan yang ingin Anda ketahui."
    }
  ]
}
```

## Field Request

- `message` wajib, string, maksimal 4000 karakter.
- `history` opsional, array, maksimal 20 item.
- `history[].role` wajib jika `history` diisi. Nilai yang valid: `user`, `assistant`, `system`.
- `history[].content` wajib jika `history` diisi, string, maksimal 4000 karakter.

## Contoh Fetch

```js
const response = await fetch('/api/chatbot/message', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    Accept: 'application/json',
    Authorization: `Bearer ${token}`,
  },
  body: JSON.stringify({
    message: inputMessage,
    history: chatHistory.map((item) => ({
      role: item.role,
      content: item.content,
    })),
  }),
});

const result = await response.json();
```

## Contoh Axios

```js
import axios from 'axios';

const result = await axios.post(
  '/api/chatbot/message',
  {
    message: inputMessage,
    history: chatHistory,
  },
  {
    headers: {
      Authorization: `Bearer ${token}`,
      Accept: 'application/json',
    },
  }
);
```

## Response Sukses

```json
{
  "status": true,
  "message": "Balasan chatbot berhasil dibuat.",
  "data": {
    "reply": "Saat ini tersedia promo creambath 20% untuk layanan tertentu. Jika Anda ingin, saya bisa bantu jelaskan detailnya.",
    "model": "openrouter/model-name",
    "id": "chatcmpl_xxx",
    "usage": {
      "prompt_tokens": 120,
      "completion_tokens": 35,
      "total_tokens": 155
    }
  }
}
```

## Response Error 500

```json
{
  "status": false,
  "message": "Gagal memproses permintaan chatbot.",
  "error": "Pesan error dari server"
}
```

## Response Validasi 422

```json
{
  "message": "The message field is required.",
  "errors": {
    "message": [
      "The message field is required."
    ]
  }
}
```

## Catatan Frontend

- Simpan riwayat chat di state lalu kirim ulang ke field `history` pada request berikutnya.
- Tampilkan jawaban chatbot dari `data.reply`.
- Jika token login tidak ada atau tidak valid, request akan gagal karena endpoint ini dilindungi `auth:sanctum`.
