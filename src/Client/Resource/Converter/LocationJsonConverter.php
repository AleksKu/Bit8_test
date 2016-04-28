<?php
namespace Bit8\Client\Resource\Converter;

use Bit8\Location\Coordinate;
use Bit8\Location\Location;
use Bit8\Location\LocationCollection;


class LocationJsonConverter implements JsonDataConverterInterface
{


    /**
     * Converts JSON to Location data structure.
     *
     * @param mixed $jsonData The JSON data.
     * @param array $options Additional implementation-specific conversion options.
     *
     * @return mixed The converted data.
     *
     */
    public function fromJson($jsonData, array $options = array())
    {
        $locationCollection = new LocationCollection();

        foreach ($jsonData as $row) {
            $location = new Location();
            $location->setName($row->name);

            $coordinate = new Coordinate($row->coordinates->lat, $row->coordinates->long);
            $location->setCoordinates($coordinate);
            $locationCollection->append($location);
        }

        return $locationCollection;

    }
}