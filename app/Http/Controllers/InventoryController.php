<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryQuantityRequest;
use App\Services\InventoryService;

class InventoryController extends Controller
{
    /** @var InventoryService $inventoryService */
    private $inventoryService;

    /**
     * Constructor method
     */
    public function __construct()
    {
        $this->inventoryService = app(InventoryService::class);
    }

    /**
     * Inventory main function to get requested inventory and
     * pass that request to inventory service to calculate stock amount
     *
     * @param InventoryQuantityRequest $inventoryQuantityRequest
     * @return array
     */
    protected function index(InventoryQuantityRequest $inventoryQuantityRequest): array
    {
        return $this->inventoryService->getStockAmount($inventoryQuantityRequest);
    }
}
