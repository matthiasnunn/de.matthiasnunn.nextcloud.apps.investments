<?php

namespace OCA\Investments\Jobs;

use OCA\Investments\InvestmentsDevelopmentUpdate;
use OCA\Investments\AppInfo\Application;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;
use OCP\ILogger;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;
use OCP\Http\Client\IClientService;


class DailyInvestmentsDevelopmentUpdateJob extends TimedJob
{
    private $clientService;
    private $logger;
    private $userFilesSerivce;


    public function __construct(IClientService $clientService, ILogger $logger, ITimeFactory $time)
    {
        parent::__construct($time);

        $this->clientService = $clientService;
        $this->logger = $logger;
        $this->userFilesSerivce = new UserFilesService(User::USER);
    }


    protected function run($arguments): void
    {
        if (!$this->isInExecutionPeriod())
        {
            return;
        }

        $this->logger->info("DailyInvestmentsDevelopmentUpdateJob started", ["app" => Application::APP_ID]);

        $investmentsDevelopmentUpdate = new InvestmentsDevelopmentUpdate($this->clientService, $this->logger, $this->userFilesSerivce);
        $investmentsDevelopmentUpdate->updateAll();
    }


    private function isInExecutionPeriod(): bool
    {
        // Wird ausgeführt um:
        // - 04:55 Uhr (Winterzeit)
        // - 05:55 Uhr (Sommerzeit)

        $currentDay = (int) date("N");     // Tag von 1 (Montag) bis 7 (Sonntag)
        $currentHour = (int) date("G");    // Stunde von 0 bis 23
        $currentMinute = (int) date("i");  // Minute von 0 bis 59

        return $currentDay >= 1 && $currentDay <= 6 && $currentHour === 3 && $currentMinute >= 55;
    }
}