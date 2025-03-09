<?php

namespace OCA\Investments\Jobs;

use OCA\Investments\AppInfo\Application;
use OCP\ILogger;
use OCP\AppFramework\Utility\ITimeFactory;
use OCP\BackgroundJob\TimedJob;


class HourlyInvestmentsAlarmJob extends TimedJob
{
    private ILogger $logger;


    public function __construct(ILogger $logger, ITimeFactory $time)
    {
        parent::__construct($time);
        parent::setInterval(3600);   // run once an hour

        $this->logger = $logger;
    }


    protected function run($arguments): void
    {
        $this->logger->info("HourlyInvestmentsAlarmJob started", ["app" => Application::APP_ID]);

        $this->...->...();
    }
}