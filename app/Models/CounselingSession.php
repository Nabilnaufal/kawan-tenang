<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CounselingSession extends Model
{
    protected $fillable = ['user_id', 'counselor_id', 'topic', 'note', 'counselor_note', 'status', 'closed_at'];

    protected function casts(): array
    {
        return ['note' => 'encrypted', 'counselor_note' => 'encrypted', 'closed_at' => 'datetime'];
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
