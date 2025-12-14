<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ImageArrayCast implements CastsAttributes
{
    /**
     * Cast nilai dari database ke array PHP.
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (is_null($value)) {
            return [];
        }

        // Jika sudah berupa array JSON, decode
        $decoded = json_decode($value, true);

        // Jika gagal decode, berarti bukan JSON → kembalikan sebagai array satu elemen
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [$value];
        }

        return $decoded;
    }

    /**
     * Cast nilai dari array PHP ke bentuk yang akan disimpan di database.
     */
    public function set($model, string $key, $value, array $attributes)
{
    if (is_string($value)) {
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $value = $decoded;
        }
    }

    if (!is_array($value)) {
        $value = [$value];
    }

    // gunakan JSON_UNESCAPED_SLASHES agar tidak menampilkan "\/"
    return json_encode($value, JSON_UNESCAPED_SLASHES);
}

}
