xml-iterator 
============

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

XML Reader to array/object iterator with low memory usage (basically a Space–time tradeoff) and an acceptable level of ease of use.
Mostly useful for importing sequential data from external API's.


## Installation (Composer)

```bash
$ composer require "halilim/xml-iterator"
```


## Usage

```php
use XmlIterator\XmlIterator;
$it = new XmlIterator("http://api.example.com/products.xml", "product");

foreach ($it as $k => $v) {
    // Do something with each row ($v), save it to db, echo it, etc. E.g.:
    // echo $k . " => " . var_export($v, true) . "\n\n";
}
```

Note: When working with remote files it's advised to copy the file to a temporary local location first.
Otherwise if the import takes a long time, "extra content at the end of the document" kind of errors may occur (or at least this was what happened to me).

Example input:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<products>
    <product>
        <title>Lorem</title>
        <brand>ACME</brand>
        <images>
            <image>http://www.example.com/image1.jpg</image>
            <image>http://www.example.com/image2.jpg</image>
        </images>
    </product>
    <product>
        <title>Ipsum</title>
        <brand>Etc LLC</brand>
        <images>
            <image>http://www.example.com/image3.jpg</image>
            <image>http://www.example.com/image4.jpg</image>
        </images>
    </product>
</products>
```

Example output:
```php
0 => array (
  'title' => 'Lorem',
  'brand' => 'ACME',
  'images' =>
  array (
    'image' =>
    array (
      0 => 'http://www.example.com/image1.jpg',
      1 => 'http://www.example.com/image2.jpg',
    ),
  ),
)

1 => array (
  'title' => 'Ipsum',
  'brand' => 'Etc LLC',
  'images' =>
  array (
    'image' =>
    array (
      0 => 'http://www.example.com/image3.jpg',
      1 => 'http://www.example.com/image4.jpg',
    ),
  ),
)
```


## Testing

``` bash
$ composer test
```


## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/halilim/xml-iterator.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/halilim/xml-iterator/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/halilim/xml-iterator.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/halilim/xml-iterator.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/halilim/xml-iterator.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/halilim/xml-iterator
[link-travis]: https://travis-ci.org/halilim/xml-iterator
[link-scrutinizer]: https://scrutinizer-ci.com/g/halilim/xml-iterator/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/halilim/xml-iterator
[link-downloads]: https://packagist.org/packages/halilim/xml-iterator
[link-author]: https://github.com/halilim
[link-contributors]: ../../contributors