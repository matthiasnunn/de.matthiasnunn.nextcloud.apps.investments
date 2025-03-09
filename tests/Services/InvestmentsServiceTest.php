<?php

namespace OCA\Investments\Tests\Services;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\FinanzenNetRepository;
use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Investments\Services\FinanzenService;
use OCA\Investments\Services\InvestmentsService;
use OCA\Investments\Services\MailService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;
use OCP\ILogger;
use OCP\Mail\IMailer;


class InvestmentsServiceTest
{
    private $investmentsService;


    public function __construct()
    {
        $finanzenRepository = new FinanzenNetRepository();
        $finanzenService = new FinanzenService($finanzenRepository);

        $userFilesService = new UserFilesService(User::ADMIN);
        $investmentsRepository = new InvestmentsRepository($userFilesService);

        $mailService = new MailService(\OC::$server->get(ILogger::class), \OC::$server->get(IMailer::class));;

        $this->investmentsService = new InvestmentsService($finanzenService, $investmentsRepository, $mailService);

        $this->checkInvestments();
        $this->getInvestmentsByTypeId(1);
        $this->getInvestmentsByTypeId(2);
        $this->getInvestmentsByTypeId(3);
        $this->getInvestmentsByTypeId(4);
    }


    private function checkInvestments()
    {
        $result = $this->investmentsService->checkInvestments();

        print_r($result);
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
}


new InvestmentsServiceTest();