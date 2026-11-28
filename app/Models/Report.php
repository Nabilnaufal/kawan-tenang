<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Report extends Model
{
    public const STATUS_BARU = 'baru';

    public const STATUS_DITINJAU = 'ditinjau';

    public const STATUS_DITINDAKLANJUTI = 'ditindaklanjuti';

    public const STATUS_DITOLAK = 'ditolak';

    protected $fillable = ['reporter_id', 'reportable_type', 'reportable_id', 'reason', 'note', 'status'];

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reportable(): MorphTo
    {
        return $this->morphTo();
    }
}
