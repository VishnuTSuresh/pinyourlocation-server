<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class holidayApiTest extends TestCase
{
    use MakeholidayTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateholiday()
    {
        $holiday = $this->fakeholidayData();
        $this->json('POST', '/api/v1/holidays', $holiday);

        $this->assertApiResponse($holiday);
    }

    /**
     * @test
     */
    public function testReadholiday()
    {
        $holiday = $this->makeholiday();
        $this->json('GET', '/api/v1/holidays/'.$holiday->id);

        $this->assertApiResponse($holiday->toArray());
    }

    /**
     * @test
     */
    public function testUpdateholiday()
    {
        $holiday = $this->makeholiday();
        $editedholiday = $this->fakeholidayData();

        $this->json('PUT', '/api/v1/holidays/'.$holiday->id, $editedholiday);

        $this->assertApiResponse($editedholiday);
    }

    /**
     * @test
     */
    public function testDeleteholiday()
    {
        $holiday = $this->makeholiday();
        $this->json('DELETE', '/api/v1/holidays/'.$holiday->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/holidays/'.$holiday->id);

        $this->assertResponseStatus(404);
    }
}
