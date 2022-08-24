<?php

namespace App\Services;

use App\Http\Requests\FormRequest;

class InventoryService
{
    const STOCK_PURCHASE = 'Purchase';
    const STOCK_APPLICATION = 'Application';

    /** @var CsvDataService $csvDataService */
    private $csvDataService;
    /** @var FormRequest $request */
    private $request;

    /**
     * Inventory service constructor which initializes CSV data service to get csv data
     */
    public function __construct()
    {
        $this->csvDataService = new CsvDataService(storage_path('fertiliser_inventory_movements.csv'));
    }

    /**
     * Function called from controller, and it will calculate the total amount of requested stock quantity
     *
     * @param FormRequest $formRequest
     * @return false|float
     */
    public function getStockAmount(FormRequest $formRequest)
    {
        $this->request = $formRequest;
        /**
         * Check how much stock qty is available using data from CSV
         */
        $availableStock = $this->getAvailableStock($this->csvDataService->getData());
        /**
         * if available stock array is empty then simply return
         */
        if (empty($availableStock)) {
            return [
                'success' => false,
                'errors' => 'There are no stock available at the moment'
            ];
        }
        /**
         * Check if requested quantity stock is present or not
         */
        if (!$this->checkRequestedQuantityIsAvailable($availableStock)) {
            return [
                'success' => false,
                'errors' => 'Requested stock quantity is not available'
            ];
        }
        /**
         * calculating the amount of requested stock qty
         */
        $stockAmount = $this->calculateStockAmount($availableStock);

        return [
            'success' => true,
            'data' => [
                'amount' => $stockAmount
            ]
        ];
    }

    /**
     * Get available stock from CSV data using Purchase and Application type
     *
     * @param array $inventoryStockData
     * @return array|void
     */
    public function getAvailableStock(array $inventoryStockData = array())
    {
        if (empty($inventoryStockData))
            return false;
        $totalAvailableStock = [];
        foreach ($inventoryStockData as $stock) {
            /**
             * If stock is purchased then add in available stock array
             */
            if ($stock['Type'] == self::STOCK_PURCHASE) {
                array_push($totalAvailableStock, $stock);
            } elseif ($stock['Type'] == self::STOCK_APPLICATION) {
                /**
                 * if stock is applied then subtract from first purchased stock
                 * which is happening in this foreach loop
                 */
                foreach ($totalAvailableStock as $key => $availableStock) {
                    $usedQuantity = 0;
                    /**
                     * Check if quantity is greater than 0 then subtract from available stock array
                     */
                    if (abs($stock['Quantity']) > 0) {
                        $usedQuantity = abs($stock['Quantity']) - $availableStock['Quantity'];
                        if ($usedQuantity == 0) {
                            unset($totalAvailableStock[$key]);
                            $stock['Quantity'] = $usedQuantity;
                        }
                    }
                    /**
                     * assigning the used quantity to stock qty for calculating amount purpose later on
                     */
                    if ($usedQuantity > 0) {
                        unset($totalAvailableStock[$key]);
                        $stock['Quantity'] = $usedQuantity;
                    } else if ($usedQuantity < 0) {
                        $stock['Quantity'] = 0;
                        $availableStock['Quantity'] = abs($usedQuantity);
                        $totalAvailableStock[$key] = $availableStock;
                    }
                }
            }
        }
        return array_values($totalAvailableStock);
    }

    /**
     * @param array $availableStock
     * @return bool
     */
    private function checkRequestedQuantityIsAvailable(array $availableStock = []): bool
    {
        $requestedQuantity = $this->request->get('quantity');
        /**
         * find total available stock by summing all the quantity
         */
        $totalAvailableQuantity = array_sum(array_column($availableStock, 'Quantity'));
        if ($requestedQuantity > $totalAvailableQuantity) {
            return false;
        }
        return true;
    }

    /**
     * Calculate the amount of requested quantity
     *
     * @param array $availableStock
     * @return float
     */
    private function calculateStockAmount(array $availableStock = []): float
    {
        $totalStockAmount = 0;
        $requestedQuantity = $this->request->get('quantity');
        foreach ($availableStock as $stock) {
            /**
             * Check if requested qty is greater than 0 then will proceed only
             */
            if ($requestedQuantity > 0) {
                if ($requestedQuantity > $stock['Quantity']) {
                    /**
                     * If requested aty is greater than stock quantity then it will utilise full qty and calculate the
                     * amount and later on it will subtract the stock qty from requested so will get remaining qty
                     */
                    $totalStockAmount += $stock['Quantity'] * $stock['Unit Price'];
                } else {
                    /**
                     * It will check if requested quantity is less than each array quantity and
                     * if that is then it will simply calculate the total amount
                     */
                    $totalStockAmount += $requestedQuantity * $stock['Unit Price'];
                }
                $requestedQuantity -= $stock['Quantity'];
            }
        }
        return round($totalStockAmount, 2);
    }
}
