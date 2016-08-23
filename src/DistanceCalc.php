<?php
namespace sgoranov\GoogleMapsTools;

use sgoranov\GoogleMapsTools\Api\Distancematrix;

class DistanceCalc
{
    protected $route = array();

    public function addPoint(Point $point)
    {
        $this->route[] = $point;
    }

    public function calculate()
    {
        if (count($this->route) < 2) {
            throw new \InvalidArgumentException('Please add at least two points before execute calculate');
        }

        $result = 0;

        for ($i = 0; ($i + 1) < count($this->route); $i++) {
            $start = $this->route[$i];
            $end = $this->route[$i + 1];

            $dmatric = new Distancematrix($start, $end);
            try {
                $result = $result + $dmatric->fetch();
            } catch (ApiException $e) {
                throw new \InvalidArgumentException('Unable to calculate the distance');
            }
        }

        return $result;
    }
}
