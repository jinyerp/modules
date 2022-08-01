<?php

namespace Jiny\Modules;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;

class JinyModulesServiceProvider extends ServiceProvider
{
    private $package = "modules";
    public function boot()
    {
        // 모듈: 라우트 설정
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', $this->package);

        // 데이터베이스
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('modules.php'),
        ]);

        // artisan 명령등록
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Jiny\Modules\Console\Commands\ModuleGetUrl::class,
                \Jiny\Modules\Console\Commands\ModuleRemove::class
            ]);
        }
    }

    public function register()
    {


        /* 라이브와이어 컴포넌트 등록 */
        $this->app->afterResolving(BladeCompiler::class, function () {
            Livewire::component('ZipInstall', \Jiny\Modules\Http\Livewire\ZipInstall::class);
        });
    }

}
