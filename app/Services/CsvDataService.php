<?php

namespace App\Services;

/**
 *  CSV data service which consists functions to work with CSV file
 */
class CsvDataService
{
    /** @var string $csvFilePath */
    private $csvFilePath;

    /**
     *
     * @param null $csvFilePath
     */
    public function __construct($csvFilePath = null)
    {
        $this->csvFilePath = $csvFilePath;
    }

    /**
     * Read data from csv file and make array from csv data combining header with each array
     *
     * @return array|false
     */
    public function getData()
    {
        //Check if file exists or not
        if (!@file_exists($this->csvFilePath)) {
            return false;
        }
        // Read data from csv file path
        $csvData = array_map('str_getcsv', file($this->csvFilePath));
        // combines the csv data with csv header
        array_walk($csvData, function (&$a) use ($csvData) {
            $a = array_combine($csvData[0], $a);
        });
        // removes the header from the array.
        array_shift($csvData);
        return $csvData;
    }
}
