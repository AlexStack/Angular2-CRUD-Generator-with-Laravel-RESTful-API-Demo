<?php

use App\Models\Location;
use App\Repositories\LocationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocationRepositoryTest extends TestCase
{
    use MakeLocationTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var LocationRepository
     */
    protected $locationRepo;

    public function setUp()
    {
        parent::setUp();
        $this->locationRepo = App::make(LocationRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateLocation()
    {
        $location = $this->fakeLocationData();
        $createdLocation = $this->locationRepo->create($location);
        $createdLocation = $createdLocation->toArray();
        $this->assertArrayHasKey('id', $createdLocation);
        $this->assertNotNull($createdLocation['id'], 'Created Location must have id specified');
        $this->assertNotNull(Location::find($createdLocation['id']), 'Location with given id must be in DB');
        $this->assertModelData($location, $createdLocation);
    }

    /**
     * @test read
     */
    public function testReadLocation()
    {
        $location = $this->makeLocation();
        $dbLocation = $this->locationRepo->find($location->id);
        $dbLocation = $dbLocation->toArray();
        $this->assertModelData($location->toArray(), $dbLocation);
    }

    /**
     * @test update
     */
    public function testUpdateLocation()
    {
        $location = $this->makeLocation();
        $fakeLocation = $this->fakeLocationData();
        $updatedLocation = $this->locationRepo->update($fakeLocation, $location->id);
        $this->assertModelData($fakeLocation, $updatedLocation->toArray());
        $dbLocation = $this->locationRepo->find($location->id);
        $this->assertModelData($fakeLocation, $dbLocation->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteLocation()
    {
        $location = $this->makeLocation();
        $resp = $this->locationRepo->delete($location->id);
        $this->assertTrue($resp);
        $this->assertNull(Location::find($location->id), 'Location should not exist in DB');
    }
}
