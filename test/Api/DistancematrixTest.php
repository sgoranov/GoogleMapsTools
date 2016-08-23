<?php
namespace sgoranov\GoogleMapsTools\Test\Api;

use \PHPUnit_Framework_TestCase as PHPUnit_Framework_TestCase;
use sgoranov\GoogleMapsTools\Api\Distancematrix;
use sgoranov\GoogleMapsTools\Point;

class DistancematrixTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorInvalidStart()
    {
        try {
            $dmatrix = new Distancematrix('string', new Point('42.6968461', '23.3358899'));
        } catch (\Throwable $t) {
            $this->assertContains('must be an instance of sgoranov\GoogleMapsTools\Point', $t->getMessage());
        } catch (\Exception $e) {
            $this->assertContains('must be an instance of sgoranov\GoogleMapsTools\Point', $e->getMessage());
        }
    }

    public function testConstructorInvalidEnd()
    {
        try {
            $dmatrix = new Distancematrix(new Point('42.6968461', '23.3358899'), 'string');
        } catch (\Throwable $t) {
            $this->assertContains('must be an instance of sgoranov\GoogleMapsTools\Point', $t->getMessage());
        } catch (\Exception $e) {
            $this->assertContains('must be an instance of sgoranov\GoogleMapsTools\Point', $e->getMessage());
        }
    }

    public function testConstructorInvalidConfigType()
    {
        try {
            $dmatrix = new Distancematrix(new Point('42.6930337', '23.3353448'), new Point('42.6968461', '23.3358899'), 'string');
        } catch (\Throwable $t) {
            $this->assertContains('must be of the type array', $t->getMessage());
        } catch (\Exception $e) {
            $this->assertContains('must be of the type array', $e->getMessage());
        }
    }
}
