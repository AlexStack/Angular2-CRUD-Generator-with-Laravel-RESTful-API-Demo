<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UploadImageApiTest extends TestCase
{
    use MakeUploadImageTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateUploadImage()
    {
        $uploadImage = $this->fakeUploadImageData();
        $this->json('POST', '/api/v1/uploadImages', $uploadImage);

        $this->assertApiResponse($uploadImage);
    }

    /**
     * @test
     */
    public function testReadUploadImage()
    {
        $uploadImage = $this->makeUploadImage();
        $this->json('GET', '/api/v1/uploadImages/'.$uploadImage->id);

        $this->assertApiResponse($uploadImage->toArray());
    }

    /**
     * @test
     */
    public function testUpdateUploadImage()
    {
        $uploadImage = $this->makeUploadImage();
        $editedUploadImage = $this->fakeUploadImageData();

        $this->json('PUT', '/api/v1/uploadImages/'.$uploadImage->id, $editedUploadImage);

        $this->assertApiResponse($editedUploadImage);
    }

    /**
     * @test
     */
    public function testDeleteUploadImage()
    {
        $uploadImage = $this->makeUploadImage();
        $this->json('DELETE', '/api/v1/uploadImages/'.$uploadImage->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/uploadImages/'.$uploadImage->id);

        $this->assertResponseStatus(404);
    }
}
