<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogger
{
    public function __construct(private Request $request)
    {
    }

    public function log(string $action, ?Model $subject = null, ?string $description = null): void
    {
        ActivityLog::create([
            'user_id' => $this->request->user()?->id,
            'action' => $action,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'description' => $description,
            'ip_address' => $this->request->ip(),
            'user_agent' => (string) $this->request->userAgent(),
        ]);
    }
}

