<?php

namespace OCA\Investments\Tests\Services;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Services\InvestmentsService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;


class InvestmentsServiceTest
{
    private $investmentsService;


    public function __construct()
    {
        $this->investmentsService = new InvestmentsService(new UserFilesService(User::ADMIN));

        $this->addInvestmentDevelopment();
        $this->getInvestmentsDevelopment();
    }


    private function addInvestmentDevelopment(): void
    {
        $currentPrice = 0;
        $purchasePrice = 0;
        $reinertrag = 0;
        $rendite = 0;
        $timestamp = new \DateTime();
        $typeId = 1;

        $result = $this->investmentsService->addInvestmentDevelopment(
            $currentPrice,
            $purchasePrice,
            $reinertrag,
            $rendite,
            $timestamp,
            $typeId
        );

        print_r($result);
    }


    private function getInvestmentsDevelopment(): void
    {
        $result = $this->investmentsService->getInvestmentsDevelopment();

        print_r($result);
    }
}


new InvestmentsServiceTest();