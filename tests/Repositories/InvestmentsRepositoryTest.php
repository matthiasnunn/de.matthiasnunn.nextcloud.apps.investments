<?php

namespace OCA\Investments\Tests\Repositories;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;


class InvestmentsRepositoryTest
{
    private $investmentsRepository;


    public function __construct()
    {
        $this->investmentsRepository = new InvestmentsRepository(new UserFilesService(User::ADMIN));

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