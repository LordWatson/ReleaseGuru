<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Feature extends Model
{
    protected $casts = [
        'tags' => 'array',
    ];

    public function release(): BelongsTo
    {
        return $this->belongsTo(Release::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function bugReports(): HasMany
    {
        //
    }

    public function changeRequests(): HasMany
    {
        //
    }
}
