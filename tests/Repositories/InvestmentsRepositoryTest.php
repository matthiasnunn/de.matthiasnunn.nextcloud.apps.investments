<?php

namespace OCA\Investments\Tests\Repositories;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;
use Psr\Log\LoggerInterface;


class InvestmentsRepositoryTest
{
    private $investmentsRepository;


    public function __construct()
    {
        $userFilesService = new UserFilesService(\OC::$server->get(LoggerInterface::class), User::ADMIN);

        $this->investmentsRepository = new InvestmentsRepository($userFilesService);

        $this->getInvestmentsByTypeId(1);
        $this->getInvestmentsByTypeId(2);
        $this->getInvestmentsByTypeId(3);
        $this->getInvestmentsByTypeId(4);
    }


    private function getInvestmentsByTypeId(int $typeId)
    {
        $result = $this->investmentsRepository->getInvestmentsByTypeId($typeId);

        print_r($result);
    }
}


new InvestmentsRepositoryTest();