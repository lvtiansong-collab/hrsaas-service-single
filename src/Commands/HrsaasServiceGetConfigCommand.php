<?php

namespace Wiltechsteam\HrsaasServiceSingle\Commands;

use Illuminate\Console\Command;

class HrsaasServiceGetConfigCommand extends Command
{
    protected $signature = 'hrsaas:config';

    protected $description = 'Publish Hrsaas config file';

    public function handle()
    {
        $configPath = config_path('hrsaas.php');
        copy(__DIR__ . '/../config/hrsaas.php', $configPath);
        $this->info('Config file published: config/hrsaas.php');
    }
}
