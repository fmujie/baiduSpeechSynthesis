<?php

namespace Fmujie\BaiduSpeech;

use Illuminate\Support\ServiceProvider;

class BaiduSpeechServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
     public function boot()
    {
        // 发布配置文件
        $this->publishes([
            __DIR__.'/config/laravel-baidu-speech.php' => config_path('laravel-baidu-speech.php'), // 发布配置文件到 laravel 的config 下
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function register()
    {
         //
    }
}
