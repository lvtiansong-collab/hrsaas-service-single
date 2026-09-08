<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\OrganizationsPagingInitializationEvent;

class OrganizationsPagingInitializationEventListener
{
    public function handle(OrganizationsPagingInitializationEvent $event): void
    {
        $eventData = $event->data['message'] ?? null;
        Log::info('OrganizationsPagingInitializationEvent', ['eventData' => $eventData]);

        if (empty($eventData['organizations'])) {
            return;
        }

        $unitModel = config('hrsaas.models_namespace') . '\Unit';
        $unitMapping = config('hrsaas.unit');
        $dateColumns = config('hrsaas.date_columns', []);
        $updateColumns = array_values(array_diff($unitMapping, ['id']));

        $units = collect($eventData['organizations'])->map(function ($item) use ($unitMapping, $dateColumns) {
            return collect($unitMapping)->mapWithKeys(function ($dbColumn, $sourceField) use ($item, $dateColumns) {
                $value = $item[$sourceField] ?? null;
                if (in_array($dbColumn, $dateColumns) && !empty($value)) {
                    $value = Carbon::parse($value)->format('Y-m-d H:i:s');
                }
                return [$dbColumn => $value];
            })->toArray();
        });

        try {
            DB::transaction(function () use ($units, $unitModel, $updateColumns) {
                foreach ($units->chunk(500) as $chunk) {
                    $unitModel::query()->upsert($chunk->toArray(), ['id'], $updateColumns);
                }
            });
        } catch (\Throwable $e) {
            Log::error('组织批量同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
}
