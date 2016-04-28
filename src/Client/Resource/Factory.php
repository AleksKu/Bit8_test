<?php


namespace Bit8\Client\Resource;


use Bit8\Client;

class Factory
{


    /**
     * @param $type
     * @param Client $client
     * @return ApiAbstract
     */
    public static function create($type, Client $client)
    {


        $resourceClass = __NAMESPACE__  .'\\'. ucwords($type)."Api";
        if (class_exists($resourceClass)) {
            $resource = new $resourceClass($client);
            return $resource;
        } else {
            throw new \InvalidArgumentException("Invalid resource api type given.");
        }
    }

}