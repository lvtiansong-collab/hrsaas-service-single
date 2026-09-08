<?php

namespace Wiltechsteam\HrsaasServiceSingle\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Wiltechsteam\HrsaasServiceSingle\Events\OrganizationUpdatedEvent;

class OrganizationUpdatedEventListener
{
    public function handle(OrganizationUpdatedEvent $event): void
    {
        $organization = $event->data['message']['organization'] ?? null;
        Log::info('Organization Updated', ['organization' => $organization]);

        if (empty($organization)) {
            return;
        }

        $unitModel = config('hrsaas.models_namespace') . '\Unit';
        $unitMapping = config('hrsaas.unit');

        $unitData = collect($unitMapping)->mapWithKeys(function ($dbColumn, $sourceField) use ($organization) {
            $value = $organization[$sourceField] ?? null;
            return [$dbColumn => $value];
        })->toArray();

        try {
            $unitModel::query()->where('id', $unitData['id'])->update($unitData);
        } catch (\Throwable $e) {
            Log::error('组织更新同步失败', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'organization' => $organization,
            ]);
            throw $e;
        }
    }
}
