<?php
namespace sgoranov\GoogleMapsTools\Api;

use sgoranov\GoogleMapsTools\Point;

class Distancematrix extends RemoteCall
{
    protected $start;
    protected $end;
    protected $config = array();

    const FETCH_DEFAULT = 'default';
    const FETCH_MAX_DISTANCE = 'max_distance';
    const FETCH_MIN_DISTANCE = 'min_distance';
    const FETCH_MAX_DURATION = 'max_duration';
    const FETCH_MIN_DURATION = 'min_duration';

    protected $validConfigOptions = array(
        'language',
        'mode',
        'units',
    );

    /**
     * Class constructor
     *
     * @param Object $start Point instance
     * @param Object $end Point instance
     * @param array $config Valid options are language, mode and units
     * */
    public function __construct(Point $start, Point $end, array $config = array())
    {
        $this->start = $start;
        $this->end = $end;

        // validate the passed config
        foreach ($config as $value) {
            if (!in_array($value, $this->validConfigOptions)) {
                throw new \InvalidArgumentException(__METHOD__ . " invalid config parameter passed - $value.");
            }
        }

        // validate the language if passed
        if (array_key_exists('language', $config)) {
            $validOptions = array(
                "ar", "bg", "bn", "ca", "cs", "da", "de", "el", "en", "en-AU", "en-GB",
                "es", "eu", "eu", "fa", "fi", "fil", "fr", "gl", "gu", "hi", "hr", "hu", "id",
                "it", "iw", "ja", "kn", "ko", "lt", "lv", "ml", "mr", "nl", "no", "pl", "pt",
                "pt-BR", "pt-PT", "ro", "ru", "sk", "sl", "sr", "sv", "ta", "te", "th", "tl",
                "tr", "uk", "vi", "zh-CN", "zh-TW",
            );
            if (!in_array($config['language'], $validOptions)) {
                throw new \InvalidArgumentException(__METHOD__ . " invalid language config parameter passed.");
            }
        }

        // validate the mode if passed
        if (array_key_exists('mode', $config)) {
            $validOptions = array(
                "driving", "walking", "bicycling",
            );
            if (!in_array($config['mode'], $validOptions)) {
                throw new \InvalidArgumentException(__METHOD__ . " invalid mode config parameter passed.");
            }
        }

        // validate the units if passed
        if (array_key_exists('units', $config)) {
            $validOptions = array(
                "metric", "imperial",
            );
            if (!in_array($config['units'], $validOptions)) {
                throw new \InvalidArgumentException(__METHOD__ . " invalid units config parameter passed.");
            }
        }

        $this->config = $config;
    }

    public function execute()
    {
        $url = 'https://maps.googleapis.com/maps/api/distancematrix/';

        $params = $this->config;
        $params['origins'] = $this->start->getLatitude() . ',' . $this->start->getLongitude();
        $params['destinations'] = $this->end->getLatitude() . ',' . $this->end->getLongitude();

        parent::__execute($url, $params);
    }

    /**
     * Fetch one of the elements from the results.
     *
     * @param string $style Use it to define which element to fetch
     * @return array
     * */
    public function fetch($style = self::FETCH_DEFAULT)
    {
        // call execute() in case of empty result
        if (is_null($this->result)) {
            $this->execute();
        }

        // validate the $style parameter
        $validOpetions = array(
            self::FETCH_DEFAULT,
            self::FETCH_MAX_DISTANCE,
            self::FETCH_MAX_DURATION.
            self::FETCH_MIN_DISTANCE.
            self::FETCH_MIN_DURATION,
        );
        if (!in_array($style, $validOpetions)) {
            throw new \InvalidArgumentException(__METHOD__ . ' invalid $style passed.');
        }


        $data = $this->fetchAll();
        $result = $data[0];

        if ($style == self::FETCH_DEFAULT) {
            return $result;
        }

        foreach ($data as $element) {
            switch ($fetch) {
                case self::FETCH_MAX_DISTANCE:
                    if ($element['distance'] > $result['distance']) {
                        $result = $element;
                    }
                    break;

                case self::FETCH_MIN_DISTANCE:
                    if ($element['distance'] < $result['distance']) {
                        $result = $element;
                    }
                    break;

                case self::FETCH_MAX_DURATION:
                    if ($element['duration'] > $result['duration']) {
                        $result = $element;
                    }
                    break;

                case self::FETCH_MIN_DURATION:
                    if ($element['duration'] < $result['duration']) {
                        $result = $element;
                    }
                    break;
            }
        }

        return $result;
    }

    /**
     * Fetch all the data from the call
     *
     * @return array
     * */
    public function fetchAll()
    {
        // call execute() in case of empty result
        if (is_null($this->result)) {
            $this->execute();
        }

        $data = array();
        foreach ($this->result['rows'][0]['elements'] as $element) {
            if ($element['status'] != 'OK') { // skip the element
                continue;
            }

            $data[] = array(
                'distance' => $element['distance']['value'],
                'distance_text' => $element['distance']['text'],
                'duration' => $element['duration']['value'],
                'duration_text' => $element['duration']['text'],
            );
        }

        return $data;
    }
}
