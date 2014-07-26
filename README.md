PHP Nearby UK Postcode Finder
==========================

The postcode finder takes a UK postcode outcode (the bit before the space) and a radius, and returns all other uk postcodes within that radius.

It is powered by a csv file of postcodes and their longitude and latitude coordinates, and shouldn't be used in production environments where speed is important. The project makes use of the excellent [Geotools](http://geotools-php.org/) library.

Installation
------------
Add to composer.json:
```json
    "require": {
        "jflight/nearby-uk-postcode-finder": "dev-master"
    }
```
Run:
```bash
composer update
```

Usage
-----
Create a postcode:
```php
$postcode = new Jflight\PostcodeFinder\UkPostcode('CF11');
```
Get postcodes within a radius of 10 km:
```php
$nearbyPostcodes = $postcode->findNearestPostcodes(10)); // Array of postcodes
```

Extension
---------
In theory, the postcode class could be extended and used for postcodes (or equivalent) for any county, as long as csv lookup of postcodes with their longitude and latitue is available.

This would be done as follows:

```php
<?php

class NewPostcode extends Jflight\PostcodeFinder\Postcode {
    protected $file = "/path/to/csv.csv";
}
```
