<?php

namespace OCA\Investments\Tests\Services;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Repositories\FinanzenNetRepository;
use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Investments\Services\FinanzenService;
use OCA\Investments\Services\InvestmentsDevelopmentService;
use OCA\Investments\Services\InvestmentsService;
use OCA\Investments\Services\MailService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;
use OCP\ILogger;
use OCP\Mail\IMailer;


class InvestmentsDevelopmentServiceTest
{
    private $investmentsDevelopmentService;


    public function __construct()
    {
        $finanzenRepository = new FinanzenNetRepository();
        $finanzenService = new FinanzenService($finanzenRepository);

        $userFilesService = new UserFilesService(User::ADMIN);
        $investmentsRepository = new InvestmentsRepository($userFilesService);

        $logger = \OC::$server->get(ILogger::class);
        $mailer = \OC::$server->get(IMailer::class);
        $mailService = new MailService($logger, $mailer);

        $investmentsService = new InvestmentsService($finanzenService, $investmentsRepository, $mailService);

        $this->investmentsDevelopmentService = new InvestmentsDevelopmentService($investmentsRepository, $investmentsService, $logger);

        $this->getInvestmentsDevelopment();
        $this->getInvestmentsTrends();
        $this->updateInvestmentsDevelopments();
    }


    private function getInvestmentsDevelopment(): void
    {
        $result = $this->investmentsDevelopmentService->getInvestmentsDevelopment();

        print_r($result);
    }


    private function getInvestmentsTrends(): void
    {
        $result = $this->investmentsDevelopmentService->getInvestmentsTrends();

        print_r($result);
    }


    private function updateInvestmentsDevelopments(): void
    {
        $this->investmentsDevelopmentService->updateInvestmentsDevelopments();
    }
}


new InvestmentsDevelopmentServiceTest();