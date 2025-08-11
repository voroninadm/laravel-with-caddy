<?php

namespace App\Providers;

use App\Http\Controllers\Repositories\MachinesRepository;
use App\Http\Interfaces\ApiSpbInterface;
use App\Http\Interfaces\EncostInterface;
use App\Http\Interfaces\MachinesInterface;
use App\Http\Repositories\EncostRepository;
use App\Http\Repositories\HttpRepository;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application Services.
     */
    public function register(): void
    {
        $this->app->bind(ApiSpbInterface::class, HttpRepository::class);
        $this->app->bind(EncostInterface::class, EncostRepository::class);
    }

    /**
     * Bootstrap any application Services.
     */
    public function boot(): void
    {
        // Подгружаем данные из вложенной в config папки constants
        foreach (glob(config_path('constants/*.php')) as $filename) {
            $key = basename($filename, '.php');
            $this->app['config']->set("constants.{$key}", require $filename);
        }
    }
}
