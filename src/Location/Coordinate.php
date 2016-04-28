<?php

//https://github.com/ayeo/geo/blob/master/src/Coordinate/Degree.php

namespace Bit8\Location;

/**
 * Class Coordinate
 * @package Bit8\Location
 */
class Coordinate
{


    /**
     * @var float
     */
    protected $longitude;
    /**
     * @var float
     */
    protected $latitude;


    public function __construct($latitude, $longitude)
    {

        $this->longitude = $longitude;
        $this->latitude = $latitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return float
     */
    public function getRadianLatitude()
    {
        return deg2rad($this->latitude);
    }

    /**
     * @return float
     */
    public function getRadianLongitude()
    {
        return deg2rad($this->longitude);
    }
}