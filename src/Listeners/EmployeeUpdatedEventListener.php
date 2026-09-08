<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\EmployeeUpdatedEvent;

class EmployeeUpdatedEventListener
{
    public function handle(EmployeeUpdatedEvent $event): void
    {
        $employee = $event->data['message']['employee'] ?? null;
        Log::info('Employee Updated ', ['employee' => $employee]);

        if (empty($employee)) {
            return;
        }

        /** @var \App\Models\Staff $staffModel */
        $staffModel = config('hrsaas.models_namespace') . '\Staff';
        $staffMapping = config('hrsaas.staff');
        $dateColumns = config('hrsaas.date_columns', []);

        $staffData = collect($staffMapping)->mapWithKeys(function ($dbColumn, $sourceField) use ($employee, $dateColumns) {
            $value = $employee[$sourceField] ?? null;
            if (in_array($dbColumn, $dateColumns) && !empty($value)) {
                $value = Carbon::parse($value)->format('Y-m-d H:i:s');
            }
            return [$dbColumn => $value];
        })->toArray();

        try {
            $staffModel::where('id', $staffData['id'])->update($staffData);
        } catch (\Throwable $e) {
            Log::error('员工更新同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'employee' => $employee,
            ]);
            throw $e;
        }
    }
}
