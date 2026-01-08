<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait BelongsToEmployer
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToEmployer(): void
    {
        // Global scope to filter by employer
        static::addGlobalScope('employer', function (Builder $builder) {
            if (Auth::check() && Auth::user()->currentEmployer) {
                $builder->where('employer_id', Auth::user()->currentEmployer->id);
            }
        });

        // Automatically set employer_id when creating
        static::creating(function ($model) {
            if (Auth::check() && Auth::user()->currentEmployer && !$model->employer_id) {
                $model->employer_id = Auth::user()->currentEmployer->id;
            }
        });
    }

    /**
     * Scope a query to include all employers (bypass global scope).
     */
    public function scopeAllEmployers(Builder $query): Builder
    {
        return $query->withoutGlobalScope('employer');
    }
}

