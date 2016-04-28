<?php


use Bit8\Client;
use Bit8\Location\Location;
use Bit8\Location\LocationCollection;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Tests\Server;
use Bit8\Client\Resource\LocationApi;


class LocationApiTest extends PHPUnit_Framework_TestCase
{

    public $validResponse = [];




    public function testResourceFactory()

    {
        $api = new Client();

        $location = $api->resource('location');

        $this->assertInstanceOf('LocationApi', $location);

        $this->setExpectedException('InvalidArgumentException');
        $location = $api->resource('invalid');


    }

    public function testGetLocations()
    {

        Server::enqueue([
            new Response(200, ['Content-Type'=> 'application/json'], json_encode($this->validResponse))
        ]);


        $api = new Client(Server::$url);

        $locations = $api->resource('location')->all();

        $this->assertInstanceOf('LocationCollection', $locations);
        $this->assertEquals($locations->count(), 2);


    }

    public function testJsonErrorResponse()
    {
        $api = new Client();


        $this->setExpectedException('ApiErrorResponseException');

        $locations = $api->resource('location')->all();


    }

    public function testJsonErrorResponseMessage()
    {
        $api = new Client();


        try {
            $locations = $api->resource('location')->all();

        } catch (ApiErrorResponseException $e) {
            $this->assertEquals($e->getCode(), 567);
            $this->assertContains($e->getMessage(), 'api failed');

        }

    }

    public function testHttpError()
    {
        $api = new Client();


        $this->setExpectedException('HttpErrorException');

        $locations = $api->resource('location')->all();



    }


}