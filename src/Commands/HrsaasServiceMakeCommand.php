<?php

namespace Wiltechsteam\HrsaasServiceSingle\Commands;

use Illuminate\Console\Command;

class HrsaasServiceMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hrsaas:make';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold Hrsaas service files (config, migrations, models)';

    /**
     * @var string
     */
    protected $modelsPath;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();

        $this->modelsPath = str_replace('App/', 'app/', str_replace('\\', '/', config('hrsaas.models_namespace')));
    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->createDirectories();

        // Publish config
        if (!file_exists(base_path('config/hrsaas.php'))) {
            copy(
                __DIR__ . '/stubs/config/hrsaas.stub',
                base_path('config/hrsaas.php')
            );
            $this->info('Config file created: config/hrsaas.php');
        } else {
            $this->warn('Config file already exists: config/hrsaas.php');
        }

        // Publish migrations
        $migrations = [
            '2026_09_03_000001_create_staffs_table.stub' => '2026_09_03_000001_create_staffs_table.php',
            '2026_09_03_000002_create_positions_table.stub' => '2026_09_03_000002_create_positions_table.php',
            '2026_09_03_000003_create_units_table.stub' => '2026_09_03_000003_create_units_table.php',
        ];

        foreach ($migrations as $stub => $filename) {
            $targetPath = database_path('migrations/' . $filename);
            copy(
                __DIR__ . '/stubs/migrations/' . $stub,
                $targetPath
            );
            $this->info('Migration created: database/migrations/' . $filename);
        }

        // Publish models
        $models = ['Staff', 'Position', 'Unit'];

        foreach ($models as $model) {
            $targetPath = base_path($this->modelsPath . '/' . $model . '.php');
            file_put_contents(
                $targetPath,
                $this->compileModelStub($model)
            );
            $this->info('Model created: ' . $this->modelsPath . '/' . $model . '.php');
        }

        $this->info('Hrsaas service scaffolding complete.');
    }

    /**
     * Create required directories.
     */
    protected function createDirectories(): void
    {
        if (!is_dir($directory = base_path($this->modelsPath))) {
            mkdir($directory, 0755, true);
        }

        if (!is_dir($directory = database_path('migrations'))) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Compile a model stub file.
     */
    protected function compileModelStub(string $model): string
    {
        return str_replace(
            '{{namespace}}',
            config('hrsaas.models_namespace'),
            file_get_contents(__DIR__ . '/stubs/models/' . $model . '.stub')
        );
    }
}
