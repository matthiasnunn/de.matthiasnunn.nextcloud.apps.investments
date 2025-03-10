<?php

namespace OCA\Investments\Jobs;

use OCA\Investments\AppInfo\Application;
use OCA\Investments\Services\InvestmentsDevelopmentService;
use OCP\ILogger;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;


class DailyInvestmentsDevelopmentUpdateJob extends TimedJob
{
    private InvestmentsDevelopmentService $investmentsDevelopmentService;
    private ILogger $logger;


    public function __construct(InvestmentsDevelopmentService $investmentsDevelopmentService, ILogger $logger, ITimeFactory $time)
    {
        parent::__construct($time);

        $this->investmentsDevelopmentService = $investmentsDevelopmentService;
        $this->logger = $logger;
    }


    protected function run($arguments): void
    {
        if (!$this->isInExecutionPeriod())
        {
            return;
        }

        $this->logger->info("DailyInvestmentsDevelopmentUpdateJob started", ["app" => Application::APP_ID]);

        $this->investmentsDevelopmentService->updateInvestmentsDevelopments();
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