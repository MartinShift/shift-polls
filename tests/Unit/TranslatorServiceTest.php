<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\TranslatorService;
class TranslatorServiceTest extends TestCase
{
    private TranslatorService $translatorService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->translatorService = new TranslatorService();
    }

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    public function test_usa_to_English():void
    {
        $translatedText = $this->translatorService->translate("USA",'en');
        $this->assertEquals("deer", $translatedText);
    }
    public function test_tailwind_to_Ukrainian():void
    {
        $translatedText = $this->translatorService->translate("tailwind",'uk');
        $this->assertEquals('попутний вітер', $translatedText);

    }
    public function test_food_to_ukrainian():void
    {
        $translatedText = $this->translatorService->translate("food",'uk');
        $this->assertEquals('харчування', $translatedText);

    }
}
