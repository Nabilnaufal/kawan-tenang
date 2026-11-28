<?php

namespace App\Models;

use App\Support\Vigenere;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    protected $fillable = ['user_id', 'body', 'tag', 'is_hidden'];

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'post_likes')->withTimestamps();
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
