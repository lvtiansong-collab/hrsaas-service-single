<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\EmployeeDeletedEvent;

class EmployeeDeletedEventListener
{
    public function handle(EmployeeDeletedEvent $event): void
    {
        $staffId = strtoupper($event->data['message']['deletedId'] ?? '');
        Log::info('Employee Deleted ', ['staffId' => $staffId]);

        if (empty($staffId)) {
            return;
        }

        $staffModel = config('hrsaas.models_namespace') . '\Staff';

        try {
            $staff = $staffModel::query()->find($staffId);

            if (empty($staff)) {
                return;
            }

            $staff->delete();
        } catch (\Throwable $e) {
            Log::error('员工删除同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'staff_id' => $staffId,
            ]);
            throw $e;
        }
    }
}
