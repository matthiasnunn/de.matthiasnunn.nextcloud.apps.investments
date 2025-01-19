<?php

namespace OCA\Investments\Tests\Services;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Services\ApiService;
use OCP\Http\Client\IClientService;


class ApiServiceTest
{
    private $clientService;


    public function __construct()
    {
        $this->clientService = \OC::$server->get(IClientService::class);

        $this->getInvestmentsByType("Aktie");
        $this->getInvestmentsByType("Devise");
        $this->getInvestmentsByType("ETF");
        $this->getInvestmentsByType("Rohstoff");
    }


    private function getInvestmentsByType(string $type)
    {
        $result = ApiService::getInvestmentsByType($this->clientService, $type);

        print_r($result);
    }
}


new ApiServiceTest();