<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    #[Scope]
    protected function activeTasksCount(Builder $query): Builder
    {
        return $query->withCount([
            'tasks as active_tasks_count' => function ($query){
                $query->whereIn('status', ['approved', 'in progress']);
            }
        ]);
    }
}
