<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\PositionAddedEvent;

class PositionAddedEventListener
{
    public function handle(PositionAddedEvent $event): void
    {
        $position = $event->data['message']['position'] ?? null;
        Log::info("Position added", ['position' => $position]);

        if (empty($position)) {
            return;
        }

        $positionModel = config('hrsaas.models_namespace') . '\Position';
        $positionMapping = config('hrsaas.position');

        $positionData = collect($positionMapping)->mapWithKeys(function ($dbColumn, $sourceField) use ($position) {
            return [$dbColumn => $position[$sourceField] ?? null];
        })->toArray();

        try {
            $positionModel::updateOrCreate(['id' => $positionData['id']], $positionData);
        } catch (\Throwable $e) {
            Log::error('职位新增同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'position' => $position,
            ]);
            throw $e;
        }
    }
}
