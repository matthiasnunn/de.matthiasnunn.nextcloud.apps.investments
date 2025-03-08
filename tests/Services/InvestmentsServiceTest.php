<?php

namespace OCA\Investments\Tests\Services;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\FinanzenNetRepository;
use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Investments\Services\InvestmentsService;
use OCA\Investments\Services\FinanzenService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;


class InvestmentsServiceTest
{
    private $investmentsService;


    public function __construct()
    {
        $finanzenRepository = new FinanzenNetRepository();
        $finanzenService = new FinanzenService($finanzenRepository);

        $userFilesService = new UserFilesService(User::ADMIN);
        $investmentsRepository = new InvestmentsRepository($userFilesService);

        $this->investmentsService = new InvestmentsService($finanzenService, $investmentsRepository);

        $this->addInvestmentDevelopment();
        $this->getInvestmentsByTypeId(1);
        $this->getInvestmentsByTypeId(2);
        $this->getInvestmentsByTypeId(3);
        $this->getInvestmentsByTypeId(4);
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

        $this->investmentsService->addInvestmentDevelopment(
            $currentPrice,
            $purchasePrice,
            $reinertrag,
            $rendite,
            $timestamp,
            $typeId
        );
    }


    private function getInvestments()
    {
        $result = $this->investmentsService->getInvestments();

        print_r($result);
    }


    private function getInvestmentsByTypeId(string $typeId)
    {
        $result = $this->investmentsService->getInvestmentsByTypeId($typeId);

        print_r($result);
    }


    private function getInvestmentsDevelopment(): void
    {
        $result = $this->investmentsService->getInvestmentsDevelopment();

        print_r($result);
    }
}


new InvestmentsServiceTest();