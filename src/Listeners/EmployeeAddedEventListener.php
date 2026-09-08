<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\EmployeeAddedEvent;

class EmployeeAddedEventListener
{
    public function handle(EmployeeAddedEvent $event): void
    {
        $employee = $event->data['message']['employee'] ?? null;
        Log::info('Employee Added ', ['employee' => $employee]);

        if (empty($employee)) {
            return;
        }

        $staffModel = config('hrsaas.models_namespace') . '\Staff';
        $staffMapping = config('hrsaas.staff');
        $dateColumns = config('hrsaas.date_columns', []);

        //处理字段数据
        $staffData = collect($staffMapping)->mapWithKeys(function ($dbColumn, $sourceField) use ($employee, $dateColumns) {
            $value = $employee[$sourceField] ?? null;
            if (in_array($dbColumn, $dateColumns) && !empty($value)) {
                $value = Carbon::parse($value)->format('Y-m-d H:i:s');
            }
            return [$dbColumn => $value];
        })->toArray();

        try {
            $staffModel::updateOrCreate(['id' => $staffData['id']], $staffData);
        } catch (\Throwable $e) {
            Log::error('员工新增同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'employee' => $employee,
            ]);
            throw $e;
        }
    }
}
