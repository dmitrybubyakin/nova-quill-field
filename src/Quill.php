<?php

namespace DmitryBubyakin\NovaQuillField;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\Expandable;
use Illuminate\Contracts\Validation\Rule;

class Quill extends Field
{
    use Expandable;

    public $component = 'nova-quill-field';

    public $showOnIndex = false;

    public $storeImagesUsing;

    public $imageRules = [];

    public function imageRules($rules)
    {
        $this->imageRules = ($rules instanceof Rule || is_string($rules)) ? func_get_args() : $rules;

        return $this;
    }

    public function getImageRules(): array
    {
        return [
            'file' => is_callable($this->imageRules)
                        ? call_user_func($this->imageRules, $request)
                        : $this->imageRules,
        ];
    }

    public function storeImagesUsing(callable $storeImagesUsing, array $parameters = []): self
    {
        $this->storeImagesUsing = $storeImagesUsing;

        return $this->withMeta([
            'imageUploadParameters' => $parameters,
        ]);
    }

    public function toolbar(array $toolbar): self
    {
        return $this->withMeta(['toolbar' => $toolbar]);
    }

    public function placeholder(string $placeholder): self
    {
        return $this->withMeta(['placeholder' => $placeholder]);
    }

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'shouldShow' => $this->shouldBeExpanded(),
        ]);
    }
}
