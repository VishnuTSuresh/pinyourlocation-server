<?php

use Faker\Factory as Faker;
use App\Models\holiday;
use App\Repositories\holidayRepository;

trait MakeholidayTrait
{
    /**
     * Create fake instance of holiday and save it in database
     *
     * @param array $holidayFields
     * @return holiday
     */
    public function makeholiday($holidayFields = [])
    {
        /** @var holidayRepository $holidayRepo */
        $holidayRepo = App::make(holidayRepository::class);
        $theme = $this->fakeholidayData($holidayFields);
        return $holidayRepo->create($theme);
    }

    /**
     * Get fake instance of holiday
     *
     * @param array $holidayFields
     * @return holiday
     */
    public function fakeholiday($holidayFields = [])
    {
        return new holiday($this->fakeholidayData($holidayFields));
    }

    /**
     * Get fake data of holiday
     *
     * @param array $postFields
     * @return array
     */
    public function fakeholidayData($holidayFields = [])
    {
        $fake = Faker::create();

        return array_merge([
            'name' => $fake->text,
            'date' => $fake->word,
            'created_at' => $fake->word,
            'updated_at' => $fake->word
        ], $holidayFields);
    }
}
