# GoogleMapsTools
PHP wrapper for Google Maps API

## Author
Simeon Goranov - sgoranov@gmail.com

## Installation

Update your composer.json adding following

```
"require": {
    "sgoranov/google-maps-tools": "dev-master"
},
```

Run composer update to fetch and install

```
php composer.phar update
````

## Using with Zend Framework 2

Modify config/application.config.php adding GoogleMapsTools in modules and module_listener_options/module_paths

```
'modules' => array(
    'Application',
    'GoogleMapsTools',
),

// These are various options for the listeners attached to the ModuleManager
'module_listener_options' => array(
    'module_paths' => array(
        './module',
        './vendor',
        'GoogleMapsTools' => './vendor/sgoranov/google-maps-tools',
    ),
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

To run all the tests execute following from GoogleMapsTools/src sirectory

```
phpunit GoogleMapsTools/Test/AllTests.php
```

To run one specific test only execute the following from GoogleMapsTools/src sirectory

```
phpunit GoogleMapsTools/Test/Api/DistancematrixTest.php
```



