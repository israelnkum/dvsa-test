<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Log;

trait SetUserId
{
    protected static function booted(): void
    {
        static::addGlobalScope('ordered', static function ($builder) {
            $builder->orderBy('id');
        });

        static::creating(static function ($model) {
            $model->created_by = auth()->id();
        });

        static::updating(static function ($model) {
            $model->last_updated_by = auth()->id();
        });

        static::deleting(static function ($model) {
            $model->archived_by = auth()->id();
            $model->save();
        });
    }
}
