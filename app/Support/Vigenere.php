<?php

namespace App\Support;

use InvalidArgumentException;

/**
 * Vigenère cipher for message bodies.
 *
 * Letters shift by the key (A=0, …, Z=25); the key repeats whenever it is
 * shorter than the text. Case is preserved and non-letters (spaces, numbers,
 * punctuation, emoji) pass through unchanged without consuming the key.
 */
final class Vigenere
{
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
            $code = ord($char);
            $base = match (true) {
                $code >= 65 && $code <= 90 => 65,
                $code >= 97 && $code <= 122 => 97,
                default => null,
            };

            if ($base === null) {
                $result .= $char;

                continue;
            }

            $shift = ord($key[$keyIndex % $keyLength]) - 65;
            $offset = ($code - $base + $direction * $shift + 26) % 26;
            $result .= chr($base + $offset);
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