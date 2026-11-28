<?php

namespace App\Models;

use App\Support\Vigenere;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CounselingSession extends Model
{
    public const STATUS_MENUNGGU = 'menunggu';

    public const STATUS_AKTIF = 'aktif';

    public const STATUS_SELESAI = 'selesai';

    protected $fillable = ['user_id', 'counselor_id', 'topic', 'note', 'counselor_note', 'status', 'closed_at'];

    protected function casts(): array
    {
        return ['closed_at' => 'datetime'];
    }

    protected function note(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value === null ? null : Vigenere::decrypt($value),
            set: fn (?string $value) => $value === null ? null : Vigenere::encrypt($value),
        );
    }

    protected function counselorNote(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value === null ? null : Vigenere::decrypt($value),
            set: fn (?string $value) => $value === null ? null : Vigenere::encrypt($value),
        );
    }

    public function isSelesai(): bool
    {
        return $this->status === self::STATUS_SELESAI;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
