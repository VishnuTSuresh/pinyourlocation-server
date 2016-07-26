<?php

namespace App\Repositories;

use App\Models\holiday;
use InfyOm\Generator\Common\BaseRepository;

class holidayRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return holiday::class;
    }
}
