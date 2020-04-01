<?php

namespace App\Providers\QuestionMark;

use Illuminate\Support\ServiceProvider;

class QuestionMarkServiceProvider extends ServiceProvider
{
    // 延遲註冊
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->singleton('question_mark', function ($app) {
            return new QuestionMark();
        });
    }

    public function provides()
    {
        return [QuestionMark::class];
    }
}
