<?php

namespace Tests\Unit;

use App\Support\Vigenere;
use Tests\TestCase;

class VigenereTest extends TestCase
{
    public function test_matches_the_classic_vigenere_vector(): void
    {
        config(['app.vigenere_key' => 'LEMON']);

        $this->assertSame('LXFOPVEFRNHR', Vigenere::encrypt('ATTACKATDAWN'));
        $this->assertSame('ATTACKATDAWN', Vigenere::decrypt('LXFOPVEFRNHR'));
    }

    public function test_round_trip_keeps_case_spacing_and_symbols(): void
    {
        config(['app.vigenere_key' => 'naufalnabilsigma']);

        $plain = 'Halo, aku capek hari ini! Besok 08:30 ya. 😊';

        $cipher = Vigenere::encrypt($plain);

        $this->assertNotSame($plain, $cipher);
        $this->assertSame($plain, Vigenere::decrypt($cipher));
        $this->assertStringContainsString(' ', $cipher);
        $this->assertStringContainsString('08:30', $cipher);
    }

    public function test_key_repeats_when_shorter_than_the_message(): void
    {
        config(['app.vigenere_key' => 'AB']);

        $cipher = Vigenere::encrypt('AAAAAA');

        $this->assertSame('ABABAB', $cipher);
        $this->assertSame('AAAAAA', Vigenere::decrypt($cipher));
    }

    public function test_missing_key_fails_loudly(): void
    {
        config(['app.vigenere_key' => '']);

        $this->expectExceptionMessage('VIGENERE_KEY');

        Vigenere::encrypt('halo');
    }
}