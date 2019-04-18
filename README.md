# Nova Quill Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/dmitrybubyakin/nova-quill-field.svg?style=flat-square)](https://github.com/dmitrybubyakin/nova-quill-field/releases)
[![Total Downloads](https://img.shields.io/packagist/dt/dmitrybubyakin/nova-quill-field.svg?style=flat-square)](https://packagist.org/packages/dmitrybubyakin/nova-quill-field)
[![StyleCI](https://github.styleci.io/repos/180394049/shield?branch=master)](https://github.styleci.io/repos/180394049)

## Installation

```
composer require dmitrybubyakin/nova-quill-field
```

## Usage

```php
Quill::make('Body')
    ->rules('required')
    ->imageRules('max:2000') // Image validation rules
    ->alwaysShow()
    ->storeImagesUsing(function (UploadedFile $file, Request $request) {
        // Use spatie/laravel-medialibrary to store images
        return (new static::$model)->newInstance(['uuid' => $request->uuid], true)
                            ->addMedia($file)
                            ->toMediaCollection()
                            ->getFullUrl(); // Return URL
    }, ['uuid' => $this->resolveUuid()]) // These attributes will be accessible from the request in the store callback
    ->toolbar([ // Customize toolbar
        [['header' => 1], ['header' => 2]],
        [['align' => ''], ['align' => 'center'], ['align' => 'right'], ['align' => 'justify']],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code'],
        [['list' => 'ordered']],
        ['clean'],
        ['link', 'image']
    ])
    ->placeholder('Write down something...'), // Customize placeholder
```

## Event

 - `nova-quill-field:loaded` &mdash; Called only once. You can register some global modules here.
 - `nova-quill-field:ready` &mdash; Called every time the component is mounted. You can configure Quill instance here.

```js
import ImageUploader from './ImageUploader'

// Override Image Uploader
Nova.$once('nova-quill-field:loaded', Quill => {
    Quill.register('modules/imageUploader', ImageUploader)
})

// Add icons
Nova.$once('nova-quill-field:loaded', Quill => {
    const icons = Quill.import('ui/icons')

    icons['fullscreen'] = require('!html-loader!../icons/fullscreen.svg')
    icons['fullscreenExit'] = require('!html-loader!../icons/fullscreen-exit.svg')
})

Nova.$on('nova-quill-field:ready', ({ field, quill }) => {
    if (field.attribute !== 'needed-field') return

    quill.keyboard.addBinding({
        key: 'F',
        shortKey: true,
        shiftKey: true,
        handler (range, context) {
            // do something...
        }
    })
})

```
