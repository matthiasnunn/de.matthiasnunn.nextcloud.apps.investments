<?php

namespace OCA\Investments\Tests\Services;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Investments\Services\InvestmentsDevelopmentService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;


class InvestmentsDevelopmentServiceTest
{
    private $investmentsDevelopmentService;


    public function __construct()
    {
        $userFilesService = new UserFilesService(User::ADMIN);
        $investmentsRepository = new InvestmentsRepository($userFilesService);

        $this->investmentsDevelopmentService = new InvestmentsDevelopmentService($investmentsRepository);

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

        $this->investmentsDevelopmentService->addInvestmentDevelopment(
            $currentPrice,
            $purchasePrice,
            $reinertrag,
            $rendite,
            $timestamp,
            $typeId
        );
    }


    private function getInvestmentsDevelopment(): void
    {
        $result = $this->investmentsDevelopmentService->getInvestmentsDevelopment();

        print_r($result);
    }
}


new InvestmentsDevelopmentServiceTest();