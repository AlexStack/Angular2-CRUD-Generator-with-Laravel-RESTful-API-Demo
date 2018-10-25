<?php

use App\Models\UploadImage;
use App\Repositories\UploadImageRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploadImageRepositoryTest extends TestCase
{
    use MakeUploadImageTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var UploadImageRepository
     */
    protected $uploadImageRepo;

    public function setUp()
    {
        parent::setUp();
        $this->uploadImageRepo = App::make(UploadImageRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateUploadImage()
    {
        $uploadImage = $this->fakeUploadImageData();
        $createdUploadImage = $this->uploadImageRepo->create($uploadImage);
        $createdUploadImage = $createdUploadImage->toArray();
        $this->assertArrayHasKey('id', $createdUploadImage);
        $this->assertNotNull($createdUploadImage['id'], 'Created UploadImage must have id specified');
        $this->assertNotNull(UploadImage::find($createdUploadImage['id']), 'UploadImage with given id must be in DB');
        $this->assertModelData($uploadImage, $createdUploadImage);
    }

    /**
     * @test read
     */
    public function testReadUploadImage()
    {
        $uploadImage = $this->makeUploadImage();
        $dbUploadImage = $this->uploadImageRepo->find($uploadImage->id);
        $dbUploadImage = $dbUploadImage->toArray();
        $this->assertModelData($uploadImage->toArray(), $dbUploadImage);
    }

    /**
     * @test update
     */
    public function testUpdateUploadImage()
    {
        $uploadImage = $this->makeUploadImage();
        $fakeUploadImage = $this->fakeUploadImageData();
        $updatedUploadImage = $this->uploadImageRepo->update($fakeUploadImage, $uploadImage->id);
        $this->assertModelData($fakeUploadImage, $updatedUploadImage->toArray());
        $dbUploadImage = $this->uploadImageRepo->find($uploadImage->id);
        $this->assertModelData($fakeUploadImage, $dbUploadImage->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteUploadImage()
    {
        $uploadImage = $this->makeUploadImage();
        $resp = $this->uploadImageRepo->delete($uploadImage->id);
        $this->assertTrue($resp);
        $this->assertNull(UploadImage::find($uploadImage->id), 'UploadImage should not exist in DB');
    }
}
