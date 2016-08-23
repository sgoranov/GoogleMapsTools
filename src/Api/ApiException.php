<?php
namespace sgoranov\GoogleMapsTools\Api;

class ApiException extends \Exception
{
    protected $status;

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }
}
