<?php
namespace GoogleMapsTools\Api;

abstract class RemoteCall
{
    protected $result = null;

    abstract public function execute();

    protected function __execute($url, array $params)
    {
        $url = rtrim($url, '/') . '/json?' . http_build_query($params);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ));

        $resp = curl_exec($curl);
        $error = curl_error($curl);
        if (!empty($error)) {
            throw new ApiException('Connection error ' . $error);
        }
        curl_close($curl);

        $result = json_decode($resp, true);

        $status = $result['status'];
        if ($status != 'OK') {
            $e = new ApiException('API error');
            $e->setStatus($status);
            throw $e;
        }

        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }
}
