<?php

namespace OCA\Investments\Tests;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\InvestmentsDevelopmentUpdate;
use OCA\Investments\Repositories\FinanzenNetRepository;
use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Investments\Services\FinanzenService;
use OCA\Investments\Services\InvestmentsService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;


class InvestmentsDevelopmentUpdateTest
{
    private $investmentsDevelopmentUpdate;


    public function __construct()
    {
        $logger = \OC::$server->getLogger();

        $finanzenRepository = new FinanzenNetRepository();
        $finanzenService = new FinanzenService($finanzenRepository);

        $userFilesService = new UserFilesService(User::ADMIN);
        $investmentsRepository = new InvestmentsRepository($userFilesService);

        $investmentsService = new InvestmentsService($finanzenService, $investmentsRepository);

        $this->investmentsDevelopmentUpdate = new InvestmentsDevelopmentUpdate($logger, $investmentsService);

        $this->updateAll();
    }


    private function updateAll(): void
    {
        $this->investmentsDevelopmentUpdate->updateAll();
    }
}


new InvestmentsDevelopmentUpdateTest();