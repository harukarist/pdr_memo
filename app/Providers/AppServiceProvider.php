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
        // Heroku環境でhttpsを強制する
        if (\App::environment('production')) {
            \URL::forceScheme('https');
        }
        // カラムの最大長を変更
        // MySQL5.7.7未満ではユニーク制約を付けたカラムは最大767bytesのため
        // varchar(191) * 4bytes(utf8mb4) = 764bytes となるように変更。
        Schema::defaultStringLength(191);
    }
}
