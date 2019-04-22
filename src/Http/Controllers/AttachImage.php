<?php

namespace DmitryBubyakin\NovaQuillField\Http\Controllers;

use Exception;
use Validator;
use DmitryBubyakin\NovaQuillField\Quill;
use Laravel\Nova\Http\Requests\NovaRequest;

class AttachImage extends Controller
{
    public function __invoke(NovaRequest $request)
    {
        $field = $this->getField($request);

        Validator::make($request->only('file'), $field->getImageRules())->validate();

        if (! is_callable($field->storeImagesUsing)) {
            throw new Exception('Image uploading disabled. Use Quill::storeImagesUsing');
        }

        return response()->json([
            'dataUrl' => call_user_func($field->storeImagesUsing, $request->file, $request),
        ]);
    }
}
