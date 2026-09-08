<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\PositionDeletedEvent;

class PositionDeletedEventListener
{
    public function handle(PositionDeletedEvent $event): void
    {
        $positionId = strtoupper($event->data['message']['deletedId'] ?? '');
        Log::info("Position Deleted", ['positionId' => $positionId]);

        if (empty($positionId)) {
            return;
        }

        $positionModel = config('hrsaas.models_namespace') . '\Position';

        try {
            $positionModel::where('id', $positionId)->delete();
        } catch (\Throwable $e) {
            Log::error('职位删除同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'position_id' => $positionId,
            ]);
            throw $e;
        }
    }
}
