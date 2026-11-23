<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

class Wajah
{
    /** Validate a face descriptor payload and normalize it to 128 floats. */
    public static function deskriptor(string $json): array
    {
        $wajah = json_decode($json, true);

        if (! is_array($wajah) || count($wajah) !== 128 || count(array_filter($wajah, 'is_numeric')) !== 128) {
            throw ValidationException::withMessages([
                'wajah' => 'Data wajah tidak valid. Pindai ulang.',
            ]);
        }

        return array_map('floatval', $wajah);
    }

    /** Euclidean distance between two 128-dimension face descriptors. */
    public static function jarak(array $a, array $b): float
    {
        if (count($a) !== count($b)) {
            return INF;
        }

        $jumlah = 0.0;

        foreach ($a as $i => $nilai) {
            $jumlah += ($nilai - $b[$i]) ** 2;
        }

        return sqrt($jumlah);
    }
}
