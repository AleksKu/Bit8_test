<?php

namespace Bit8\Location;

/**
 * Class Location
 * @package Bit8\Location
 */
class Location
{

    /**
     * @var Coordinate
     */
    protected $coordinates;

    /**
     * @var
     */
    protected $name;

    /**
     * @return Coordinate
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * @param mixed $coordinates
     */
    public function setCoordinates(Coordinate $coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}