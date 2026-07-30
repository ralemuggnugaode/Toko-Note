<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogActivity
{
    protected function logActivity($model, $action, $changes = null)
    {
        if (!auth()->check()) {
            return;
        }

        ActivityLog::create([
            'user_id'    => auth()->id(),
            'model_type' => get_class($model),
            'model_id'   => $model->id ?? null,
            'action'     => $action,
            'changes'    => $changes ? json_encode($changes) : null,
        ]);
    }
}
