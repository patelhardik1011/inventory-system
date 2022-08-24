<?php

namespace Tests\Feature;

use App\Http\Requests\InventoryQuantityRequest;
use App\Services\InventoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InventoryTest extends TestCase
{
    protected $testData = [
        [
            "Date" => "05/06/2020",
            "Type" => "Purchase",
            "Quantity" => "10",
            "Unit Price" => "5"
        ],
        [
            "Date" => "07/06/2020",
            "Type" => "Purchase",
            "Quantity" => "30",
            "Unit Price" => "4.5"
        ],
        [
            "Date" => "08/06/2020",
            "Type" => "Application",
            "Quantity" => "-20",
            "Unit Price" => ""
        ]
    ];

    /**
     * Pass Test stock amount
     *
     * @return void
     */
    public function testStockAmount()
    {
        $formRequest = new InventoryQuantityRequest();
        $formRequest->request->set('quantity', 5);
        $inventoryService = new InventoryService();
        $amount = $inventoryService->getStockAmount($formRequest);
        $this->assertEquals(21.0, $amount['data']['amount']);
    }

    /**
     * Test of fail stock amount
     */
    public function testFailStockAmount()
    {
        $formRequest = new InventoryQuantityRequest();
        $formRequest->request->set('quantity', '5');
        $inventoryService = new InventoryService();
        $amount = $inventoryService->getStockAmount($formRequest);
        $this->assertNotEquals(25, $amount['data']['amount']);
    }

    /**
     * Checking for available stock data
     */
    public function testAvailableStockData()
    {
        $inventoryService = new InventoryService();
        $availableStockData = $inventoryService->getAvailableStock($this->testData);
        $this->assertEquals([
            [
                "Date" => "07/06/2020",
                "Type" => "Purchase",
                "Quantity" => "20",
                "Unit Price" => "4.5"
            ]
        ], $availableStockData);
    }
}
