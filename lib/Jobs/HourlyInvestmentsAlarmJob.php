<?php

namespace OCA\Investments\Jobs;

use OCA\Investments\AppInfo\Application;
use OCA\Investments\Repositories\FinanzenNetRepository;
use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Investments\Services\FinanzenService;
use OCA\Investments\Services\InvestmentsService;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;
use OCP\ILogger;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;


class HourlyInvestmentsAlarmJob extends TimedJob
{
    private InvestmentsService $investmentsService;
    private ILogger $logger;


    public function __construct(InvestmentsService $investmentsService, ILogger $logger, ITimeFactory $time)
    {
        parent::__construct($time);
        parent::setInterval(3600);   // run once an hour

        $this->logger = $logger;
        $this->investmentsService = $investmentsService;
    }


    protected function run($arguments): void
    {
        $this->logger->info("HourlyInvestmentsAlarmJob started", ["app" => Application::APP_ID]);

        $this->investmentsService->checkInvestments();
    }
}