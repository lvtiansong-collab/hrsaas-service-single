<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\OrganizationDeletedEvent;

class OrganizationDeletedEventListener
{
    public function handle(OrganizationDeletedEvent $event): void
    {
        $unitId = strtoupper($event->data['message']['deletedId'] ?? '');
        Log::info("organization Deleted", ['unitId' => $unitId]);

        if (empty($unitId)) {
            return;
        }

        $unitModel = config('hrsaas.models_namespace') . '\Unit';

        try {
            $unitModel::where('id', $unitId)->delete();
        } catch (\Throwable $e) {
            Log::error('组织删除同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'unit_id' => $unitId,
            ]);
            throw $e;
        }
    }
}
