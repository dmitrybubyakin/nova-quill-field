<?php

namespace DmitryBubyakin\NovaQuillField\Http\Controllers;

use DmitryBubyakin\NovaQuillField\Quill;
use Laravel\Nova\Http\Requests\NovaRequest;

abstract class Controller
{
    public function getField(NovaRequest $request): Quill
    {
        return $request->newResourceWith(
            $request->findModelQuery()->first() ?: $request->model()
        )->availableFields($request)->first(function ($field) use ($request) {
            return $request->attribute === $field->attribute;
        }, function () {
            abort(404);
        });
    }
}
