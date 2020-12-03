<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Schema;
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

        // 商用環境以外の場合、storage/logs/の中にSQLログを出力する
        if (config('app.env') !== 'production') {
            DB::listen(function ($query) {
                \Log::info("Query Time:{$query->time}s] $query->sql");
            });
        }
    }
}
