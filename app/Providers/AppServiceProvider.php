<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //文字コードの影響(mysql5.6なのが原因)があり、php artisan migrateするとエラー出るので以下を追記　2022/1/31
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);
    }
}
