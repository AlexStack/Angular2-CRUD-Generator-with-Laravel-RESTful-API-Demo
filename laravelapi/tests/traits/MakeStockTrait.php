<?php

use Faker\Factory as Faker;
use App\Models\Stock;
use App\Repositories\StockRepository;

trait MakeStockTrait
{
    /**
     * Create fake instance of Stock and save it in database
     *
     * @param array $stockFields
     * @return Stock
     */
    public function makeStock($stockFields = [])
    {
        /** @var StockRepository $stockRepo */
        $stockRepo = App::make(StockRepository::class);
        $theme = $this->fakeStockData($stockFields);
        return $stockRepo->create($theme);
    }

    /**
     * Get fake instance of Stock
     *
     * @param array $stockFields
     * @return Stock
     */
    public function fakeStock($stockFields = [])
    {
        return new Stock($this->fakeStockData($stockFields));
    }

    /**
     * Get fake data of Stock
     *
     * @param array $postFields
     * @return array
     */
    public function fakeStockData($stockFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'id' => $fake->randomDigitNotNull,
            'industry' => $fake->word,
            'stock_name' => $fake->word,
            'market_cap' => $fake->word,
            'currency' => $fake->word,
            'currency_code' => $fake->word,
            'sector' => $fake->word
        ], $stockFields);
    }
}
