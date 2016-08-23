# GoogleMapsTools
PHP wrapper for Google Maps API

## Author
Simeon Goranov - sgoranov@gmail.com

## Installation

Update your composer.json adding following

```
$ composer require sgoranov/google-maps-tools
```

## Usage

Use the Google Maps Geocoding API call to search by address:

```php
$geocode = new Geocode('Car Osvoboditel 13, Sofia, Bulgaria');
$geocode->execute();
$point = $geocode->getFirstPoint();
```

The point is defined as latitude and longitude:

```php
$latitude = $point->getLatitude();
$longitude = $point->getLongitude();
```

Use Google Maps Distancematrix API call to calculate the distance between two points:

```php
$dmatrix = new Distancematrix($startPoint, $endPoint);
try {
    $result = $dmatrix->fetch();
} catch (ApiException $e) {
    throw new \InvalidArgumentException('Unable to calculate the distance');
}
```

Use DistanceCalc library to calculate the distance between multiple points:

```php
$calc = new DistanceCalc();

$geocode = new Geocode('Car Osvoboditel 13, Sofia, Bulgaria');
$geocode->execute();
$point = $geocode->getFirstPoint();
$calc->addPoint($point);

$geocode = new Geocode('Ianko Sakazov 1, Sofia, Bulgaria');
$geocode->execute();
$point = $geocode->getFirstPoint();
$calc->addPoint($point);

$geocode = new Geocode('Georgi Rakovski 96, Sofia, Bulgaria');
$geocode->execute();
$point = $geocode->getFirstPoint();
$calc->addPoint($point);

$distance = $calc->calculate();
```

## Unit testing

To run the test locally execute the following:

```
vendor/phpunit/phpunit/phpunit
```

