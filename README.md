# Inventory Cost Calculator

## Overview
This project calculates the price for the quantity required based on the stock based on purchase and application of stock.  

### Testing
Here we have some unit testing to test the application.
```
test/Feature/InventoryTest.php
```

## Installation

**To run this project, docker needs to be installed on the machine.**

To run this project please follow below steps:

1. Clone the repo from GitHub using below command:
    ````
   git clone https://github.com/patelhardik1011/inventory-system.git
2. After cloning please go to the project directory
   ````
   cd inventory-system
3. Now run below docker commands to run docker containers
   ````
   docker-compose down && docker-compose build && docker-compose up -d
4. Check if docker containers are running or not using below command. This will show docker containers running on the machine.
   ````
   docker ps
5. Copy .env.example file to .env file in the root directory 
6. Please run following command to generate the key
   ````
   docker exec inventory-container php artisan key:generate
   docker exec inventory-container php artisan cache:clear
   docker exec inventory-container php artisan config:clear

Here I have used sample CSV file to be stored in Storage folder and reading data from that CSV file.

### Run the test cases
To run the test case, open the terminal in the root directory and run the following command
 ```
 docker exec inventory-container php artisan test
 ```
