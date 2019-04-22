<?php

namespace DmitryBubyakin\NovaQuillField\Http\Controllers;

use Exception;
use Validator;
use DmitryBubyakin\NovaQuillField\Quill;
use Laravel\Nova\Http\Requests\NovaRequest;

class Save extends Controller
{
    public function __invoke(NovaRequest $request): void
    {
        call_user_func($this->getField($request)->autosaveCallback, $request);
    }
}
