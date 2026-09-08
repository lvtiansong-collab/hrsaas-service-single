<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\PositionUpdatedEvent;

class PositionUpdatedEventListener
{
    public function handle(PositionUpdatedEvent $event): void
    {
        $position = $event->data['message']['position'] ?? null;
        Log::info("Position Updated", ['position' => $position]);

        if (empty($position)) {
            return;
        }

        $positionModel = config('hrsaas.models_namespace') . '\Position';
        $positionMapping = config('hrsaas.position');

        $positionData = collect($positionMapping)->mapWithKeys(function ($dbColumn, $sourceField) use ($position) {
            return [$dbColumn => $position[$sourceField] ?? null];
        })->toArray();

        try {
            $positionModel::where('id', $positionData['id'])->update($positionData);
        } catch (\Throwable $e) {
            Log::error('职位更新同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'position' => $position,
            ]);
            throw $e;
        }
    }
}
