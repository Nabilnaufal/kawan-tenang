<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Vigenère cipher for message bodies.
 *
 * Each letter maps to a number (A = 0 … Z = 25). The key letter's number is
 * added on encrypt and subtracted on decrypt, then the result maps back to a
 * letter. The key repeats whenever it is shorter than the text. Case is
 * preserved and non-letters pass through without consuming the key.
 */
final class Vigenere
{
    /** Indeks ke huruf: 0 => A, 1 => B, … 25 => Z. */
    private const HURUF = [
        'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
        'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    ];

    /** Huruf ke indeks: A => 0, B => 1, … Z => 25. */
    private const INDEKS = [
        'A' => 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5, 'G' => 6,
        'H' => 7, 'I' => 8, 'J' => 9, 'K' => 10, 'L' => 11, 'M' => 12,
        'N' => 13, 'O' => 14, 'P' => 15, 'Q' => 16, 'R' => 17, 'S' => 18,
        'T' => 19, 'U' => 20, 'V' => 21, 'W' => 22, 'X' => 23, 'Y' => 24,
        'Z' => 25,
    ];

    public static function encrypt(string $plaintext): string
    {
        return self::shift($plaintext, 1);
    }

    public static function decrypt(string $ciphertext): string
    {
        return self::shift($ciphertext, -1);
    }

    private static function shift(string $text, int $direction): string
    {
        $key = self::key();
        $keyLength = strlen($key);
        $keyIndex = 0;
        $result = '';

        for ($i = 0, $length = strlen($text); $i < $length; $i++) {
            $char = $text[$i];
            $letter = strtoupper($char);

            if (! isset(self::INDEKS[$letter])) {
                $result .= $char;

                continue;
            }

            $shift = self::INDEKS[$key[$keyIndex % $keyLength]];
            $value = (self::INDEKS[$letter] + $direction * $shift + 26) % 26;

            $result .= $char === $letter
                ? self::HURUF[$value]
                : strtolower(self::HURUF[$value]);
            $keyIndex++;
        }

        return $result;
    }

    private static function key(): string
    {
        $key = preg_replace('/[^A-Z]/', '', strtoupper((string) config('app.vigenere_key')));

        if ($key === '') {
            throw new InvalidArgumentException('VIGENERE_KEY belum diatur di environment.');
        }

        return $key;
    }
}