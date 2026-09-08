<?php

namespace Wiltechsteam\HrsaasServiceSingle;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Wiltechsteam\HrsaasServiceSingle\Commands\HrsaasSingleCommand;
use Wiltechsteam\HrsaasServiceSingle\Commands\HrsaasServiceGetConfigCommand;
use Wiltechsteam\HrsaasServiceSingle\Commands\HrsaasServiceMakeCommand;

class HrsaasServiceSingleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->eventBoot();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/hrsaas.php', 'hrsaas');

        $this->app->singleton('hrsaas:work', HrsaasSingleCommand::class);
        $this->commands(['hrsaas:work']);

        $this->app->singleton('hrsaas:make', function () {
            return new HrsaasServiceMakeCommand();
        });
        $this->commands(['hrsaas:make']);

        $this->app->singleton('hrsaas:config', function () {
            return new HrsaasServiceGetConfigCommand();
        });
        $this->commands(['hrsaas:config']);

        $this->bindEvents();
    }

    private function bindEvents()
    {
        foreach (config('hrsaas.events') as $key => $className) {
            $this->app->bind($key, $className);
        }
    }

    /**
     * 批量绑定事件监听
     */
    public function eventBoot()
    {
        foreach (config('hrsaas.listens') as $event => $listeners) {
            foreach ($listeners as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
