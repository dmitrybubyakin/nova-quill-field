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
