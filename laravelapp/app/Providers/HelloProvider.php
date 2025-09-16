<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

class HelloProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         /*
        View::composer(
            'hello.index', 'App\Http\Composers\HelloComposer'
        ); */

        View::composer(
            'hello.index', function($view){
                $view->with('view_message', "composer message!");
            }
        ); 
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
