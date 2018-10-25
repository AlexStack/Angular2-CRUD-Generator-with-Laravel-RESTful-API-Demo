<?php

use Faker\Factory as Faker;
use App\Models\UploadImage;
use App\Repositories\UploadImageRepository;

trait MakeUploadImageTrait
{
    /**
     * Create fake instance of UploadImage and save it in database
     *
     * @param array $uploadImageFields
     * @return UploadImage
     */
    public function makeUploadImage($uploadImageFields = [])
    {
        /** @var UploadImageRepository $uploadImageRepo */
        $uploadImageRepo = App::make(UploadImageRepository::class);
        $theme = $this->fakeUploadImageData($uploadImageFields);
        return $uploadImageRepo->create($theme);
    }

    /**
     * Get fake instance of UploadImage
     *
     * @param array $uploadImageFields
     * @return UploadImage
     */
    public function fakeUploadImage($uploadImageFields = [])
    {
        return new UploadImage($this->fakeUploadImageData($uploadImageFields));
    }

    /**
     * Get fake data of UploadImage
     *
     * @param array $postFields
     * @return array
     */
    public function fakeUploadImageData($uploadImageFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'id' => $fake->randomDigitNotNull,
            'app_name' => $fake->word,
            'version' => $fake->word,
            'domain_name' => $fake->word,
            'base64_img' => $fake->word,
            'file_name' => $fake->word,
            'owner' => $fake->word,
            'uploaded_img' => $fake->word,
            'published_date' => $fake->word
        ], $uploadImageFields);
    }
}
