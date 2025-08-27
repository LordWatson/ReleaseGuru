<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $casts = [
        'tags' => 'array',
        'deployment_notes' => 'array',
        'estimated_hours' => 'decimal:2',
        'actual_hours' => 'decimal:2',
        'due_date' => 'date',
        'completed_date' => 'date',
    ];

    public function release(): BelongsTo
    {
        return $this->belongsTo(Release::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function bugReports(): BelongsTo
    {
        return $this->belongsTo(BugReport::class);
    }

    // Helper methods
    public function isOverdue(): bool
    {
        return $this->due_date &&
            $this->due_date < now() &&
            !in_array($this->status, ['completed', 'deployed', 'cancelled']);
    }
}
