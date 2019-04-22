<?php

namespace DmitryBubyakin\NovaQuillField;

use Route;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;
use DmitryBubyakin\NovaQuillField\Http\Controllers;

class FieldServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-quill-field', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-quill-field', __DIR__.'/../dist/css/field.css');
        });
    }

    protected function routes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
                ->prefix('nova-vendor/nova-quill-field')
                ->group(function () {
                    Route::post('attachment', Controllers\AttachImage::class);
                    Route::post('save', Controllers\Save::class);
                });
    }
}
