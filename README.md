xml-iterator
============

XML Reader to array/object iterator with low memory usage and an acceptable level of ease of use.
Mostly useful for importing sequential data from external API's.

Note: This is currently alpha/development stage, see todo below.


Installation
------------

Add this to your composer.json:

    {
        "repositories": [
            {
                "type": "vcs",
                "url" : "https://github.com/halilim/xml-iterator.git"
            }
        ],

        "require": {
            "halilim/xml-iterator": "dev-master"
        }
    }

Usage
-----

    use XmlIterator\XmlIterator;
    $it = new XmlIterator("http://api.example.com/products.xml", "product");

    foreach ($it as $k => $v) {
        // Do something with each row ($v), save it to db, echo it, etc. E.g.:
        // echo $k . " => " . var_export($v, true) . "\n\n";
    }

Example input:

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

Example output:

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

Todo
----

* <strike>Rename namespace and/or class (esp. the namespace can be confused with PHP SPL's `SimpleXMLIterator`)</strike>
* Submit to packagist after using it for a while.