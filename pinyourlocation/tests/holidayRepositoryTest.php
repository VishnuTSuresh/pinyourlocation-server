<?php

use App\Models\holiday;
use App\Repositories\holidayRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class holidayRepositoryTest extends TestCase
{
    use MakeholidayTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var holidayRepository
     */
    protected $holidayRepo;

    public function setUp()
    {
        parent::setUp();
        $this->holidayRepo = App::make(holidayRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateholiday()
    {
        $holiday = $this->fakeholidayData();
        $createdholiday = $this->holidayRepo->create($holiday);
        $createdholiday = $createdholiday->toArray();
        $this->assertArrayHasKey('id', $createdholiday);
        $this->assertNotNull($createdholiday['id'], 'Created holiday must have id specified');
        $this->assertNotNull(holiday::find($createdholiday['id']), 'holiday with given id must be in DB');
        $this->assertModelData($holiday, $createdholiday);
    }

    /**
     * @test read
     */
    public function testReadholiday()
    {
        $holiday = $this->makeholiday();
        $dbholiday = $this->holidayRepo->find($holiday->id);
        $dbholiday = $dbholiday->toArray();
        $this->assertModelData($holiday->toArray(), $dbholiday);
    }

    /**
     * @test update
     */
    public function testUpdateholiday()
    {
        $holiday = $this->makeholiday();
        $fakeholiday = $this->fakeholidayData();
        $updatedholiday = $this->holidayRepo->update($fakeholiday, $holiday->id);
        $this->assertModelData($fakeholiday, $updatedholiday->toArray());
        $dbholiday = $this->holidayRepo->find($holiday->id);
        $this->assertModelData($fakeholiday, $dbholiday->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteholiday()
    {
        $holiday = $this->makeholiday();
        $resp = $this->holidayRepo->delete($holiday->id);
        $this->assertTrue($resp);
        $this->assertNull(holiday::find($holiday->id), 'holiday should not exist in DB');
    }
}
