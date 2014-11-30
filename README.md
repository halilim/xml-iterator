xml-iterator [![Build Status](https://travis-ci.org/halilim/xml-iterator.png)](https://travis-ci.org/halilim/xml-iterator)
============

XML Reader to array/object iterator with low memory usage (basically a Space–time tradeoff) and an acceptable level of ease of use.
Mostly useful for importing sequential data from external API's.


Installation (Composer)
-----------------------

Add this to your composer.json:
```json
{
    "require": {
        "halilim/xml-iterator": "dev-master"
    }
}
```

Usage
-----
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

Contributing
------------
1. Fork
2. Clone your fork
3. Create a topic branch
4. Make changes
5. Add tests if possible
6. Make sure all tests are successful
7. If you are adding new functionality, document it in README.md
8. Do not change the version number
9. If necessary, rebase your commits into logical chunks, without errors
10. If there are changes in the upstream, rebase against it
11. Push your branch
12. Send a pull request for your branch

Notes
-----
* Tries to use [semantic versioning](http://semver.org/) whenever possible.
