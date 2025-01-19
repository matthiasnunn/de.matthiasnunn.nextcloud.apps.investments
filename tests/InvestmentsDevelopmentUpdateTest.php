<?php

namespace OCA\Investments\Tests;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\InvestmentsDevelopmentUpdate;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;
use OCP\Http\Client\IClientService;


class InvestmentsDevelopmentUpdateTest
{
    private $investmentsDevelopmentUpdate;


    public function __construct()
    {
        $clientService = \OC::$server->get(IClientService::class);
        $logger = \OC::$server->getLogger();
        $userFilesSerivce = new UserFilesService(User::ADMIN);

        $this->investmentsDevelopmentUpdate = new InvestmentsDevelopmentUpdate($clientService, $logger, $userFilesSerivce);

        $this->updateAll();
    }


    private function updateAll(): void
    {
        $this->investmentsDevelopmentUpdate->updateAll();
    }
}


new InvestmentsDevelopmentUpdateTest();