<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\EmployeesPagingInitializationEvent;

class EmployeesPagingInitializationEventListener
{
    public function handle(EmployeesPagingInitializationEvent $event): void
    {
        $eventData = $event->data['message'] ?? null;
        Log::info('EmployeesPagingInitializationEvent', ['eventData' => $eventData]);

        if (empty($eventData['employees'])) {
            return;
        }

        $staffModel = config('hrsaas.models_namespace') . '\Staff';
        $staffMapping = config('hrsaas.staff');
        $dateColumns = config('hrsaas.date_columns', []);
        $updateColumns = array_values(array_diff($staffMapping, ['id']));

        $staffs = collect($eventData['employees'])->map(function ($employee) use ($staffMapping, $dateColumns) {
            return collect($staffMapping)->mapWithKeys(function ($dbColumn, $sourceField) use ($employee, $dateColumns) {
                $value = $employee[$sourceField] ?? null;
                if (in_array($dbColumn, $dateColumns) && !empty($value)) {
                    $value = Carbon::parse($value)->format('Y-m-d H:i:s');
                }
                return [$dbColumn => $value];
            })->toArray();
        });

        try {
            DB::transaction(function () use ($staffs, $staffModel, $updateColumns) {
                foreach ($staffs->chunk(500) as $chunk) {
                    $staffModel::upsert($chunk->toArray(), ['id'], $updateColumns);
                }
            });
        } catch (\Throwable $e) {
            Log::error('员工初始化同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
}
