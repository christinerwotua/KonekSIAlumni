<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Tanggal tampil dalam Bahasa Indonesia (mis. "3 hari yang lalu").
        \Carbon\Carbon::setLocale('id');

        // Windows: `php artisan serve` tidak meneruskan TEMP/TMP ke server PHP,
        // akibatnya upload file gagal ("missing temporary directory").
        \Illuminate\Foundation\Console\ServeCommand::$passthroughVariables[] = 'TEMP';
        \Illuminate\Foundation\Console\ServeCommand::$passthroughVariables[] = 'TMP';
    }
}
