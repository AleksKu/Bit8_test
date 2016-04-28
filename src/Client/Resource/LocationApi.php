<?php

namespace Bit8\Client\Resource;


class LocationApi extends ApiAbstract
{

    public function all()
    {
        $response = $this->client->getHttpClient()->request('GET', 'all');
        return $response->json();
    }

}