<?php

namespace OCA\Investments\Tests;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\InvestmentsDevelopmentUpdate;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;


class InvestmentsDevelopmentUpdateTest
{
    private $investmentsDevelopmentUpdate;


    public function __construct()
    {
        $logger = \OC::$server->getLogger();
        $userFilesSerivce = new UserFilesService(User::ADMIN);

        $this->investmentsDevelopmentUpdate = new InvestmentsDevelopmentUpdate($logger, $userFilesSerivce);

        $this->updateAll();
    }


    private function updateAll(): void
    {
        $this->investmentsDevelopmentUpdate->updateAll();
    }
}


new InvestmentsDevelopmentUpdateTest();