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

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    #[Scope]
    protected function activeFeaturesCount(Builder $query): Builder
    {
        return $query->withCount([
            'features as active_features_count' => function ($query){
                $query->whereIn('status', ['approved', 'in progress']);
            }
        ]);
    }
}
