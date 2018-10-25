<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocationApiTest extends TestCase
{
    use MakeLocationTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateLocation()
    {
        $location = $this->fakeLocationData();
        $this->json('POST', '/api/v1/locations', $location);

        $this->assertApiResponse($location);
    }

    /**
     * @test
     */
    public function testReadLocation()
    {
        $location = $this->makeLocation();
        $this->json('GET', '/api/v1/locations/'.$location->id);

        $this->assertApiResponse($location->toArray());
    }

    /**
     * @test
     */
    public function testUpdateLocation()
    {
        $location = $this->makeLocation();
        $editedLocation = $this->fakeLocationData();

        $this->json('PUT', '/api/v1/locations/'.$location->id, $editedLocation);

        $this->assertApiResponse($editedLocation);
    }

    /**
     * @test
     */
    public function testDeleteLocation()
    {
        $location = $this->makeLocation();
        $this->json('DELETE', '/api/v1/locations/'.$location->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/locations/'.$location->id);

        $this->assertResponseStatus(404);
    }
}
