<?php

namespace App\Models;

use App\Support\Vigenere;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = ['sender_id', 'body', 'read_at'];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    protected function body(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value === null ? null : Vigenere::decrypt($value),
            set: fn (?string $value) => $value === null ? null : Vigenere::encrypt($value),
        );
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(CounselingSession::class, 'counseling_session_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
