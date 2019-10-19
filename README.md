# Silverstripe Find HTTP Action

[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE.md)
[![Version](http://img.shields.io/packagist/v/level51/silverstripe-find-http-action.svg?style=flat)](https://packagist.org/packages/level51/silverstripe-find-http-action)

Trait for finding controller actions by HTTP method used on the request. Handy for building CRUD based Webservices.
Can be used alongside the default action handling.

## Requirements

- Silverstripe 4.x

## Installation

- `composer require level51/silverstripe-find-http-action`
- Flush config (`flush=all`)

## Usage

```php
use Level51\FindHTTPAction\FindHTTPAction;
use SilverStripe\Control\Controller;

class MyCRUDController extends Controller {

    use FindHTTPAction;

    ...
    
    private static $url_handlers = [
        'foo/$id'  => [
            'GET'    => 'getFooRecords',
            'POST'   => 'createFooRecord',
            'PUT'    => 'updateFooRecord',
            'DELETE' => 'deleteFooRecord'
        ],
        'bar/$id!' => [
            'PUT' => 'updateBarRecord'
        ],
        'about'    => 'myRegularAction
    ];
}

```

## Maintainer

- Julian Scheuchenzuber <js@lvl51.de>


