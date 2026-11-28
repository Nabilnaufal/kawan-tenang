<?php

namespace App\Models;

use App\Support\Vigenere;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Comment extends Model
{
    protected $fillable = ['post_id', 'user_id', 'body', 'is_hidden'];

    protected function casts(): array
    {
        return ['is_hidden' => 'boolean'];
    }

    protected function body(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value === null ? null : Vigenere::decrypt($value),
            set: fn (?string $value) => $value === null ? null : Vigenere::encrypt($value),
        );
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
