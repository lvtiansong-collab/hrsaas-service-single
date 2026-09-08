<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\PositionsPagingInitializationEvent;

class PositionsPagingInitializationEventListener
{
    public function handle(PositionsPagingInitializationEvent $event): void
    {
        $eventData = $event->data['message'] ?? null;
        Log::info('PositionsPagingInitializationEvent', ['eventData' => $eventData]);

        if (empty($eventData['positions'])) {
            return;
        }

        $positionModel = config('hrsaas.models_namespace') . '\Position';
        $positionMapping = config('hrsaas.position');
        $updateColumns = array_values(array_diff($positionMapping, ['id']));

        $positions = collect($eventData['positions'])->map(function ($item) use ($positionMapping) {
            return collect($positionMapping)->mapWithKeys(function ($dbColumn, $sourceField) use ($item) {
                return [$dbColumn => $item[$sourceField] ?? null];
            })->toArray();
        });

        try {
            DB::transaction(function () use ($positions, $positionModel, $updateColumns) {
                foreach ($positions->chunk(500) as $chunk) {
                    $positionModel::upsert($chunk->toArray(), ['id'], $updateColumns);
                }
            });
        } catch (\Throwable $e) {
            Log::error('职位批量同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
}
