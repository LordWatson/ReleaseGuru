<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    public function releases(): HasMany
    {
        return $this->hasMany(Release::class);
    }
}
