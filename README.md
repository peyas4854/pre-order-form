## Installation

You can install the package via composer:

```bash
composer require peyas/pre-order-form
```

## Usage

```php
use Peyas\PreOrderForm\PreOrderForm;

    // in api.php

    // public api routes
    PreOrderForm::routes([
        'pre-order-store', // order store 
    ]);
    // auth api routes
    PreOrderForm::routes([
        'pre-order-delete', // order delete
        'pre-order-index-show-view' // order index show view
    ]);

```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email peyaschandra@gmail.com instead of using the issue tracker.

## Credits

- [Peyas Chandra Das](https://github.com/peyas)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
