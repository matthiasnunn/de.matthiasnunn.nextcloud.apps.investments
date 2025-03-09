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
    private ILogger $logger;
    private InvestmentsService $investmentsService;


    public function __construct(ILogger $logger, ITimeFactory $time)
    {
        parent::__construct($time);
        parent::setInterval(3600);   // run once an hour

        $finanzenRepository = new FinanzenNetRepository();
        $finanzenService = new FinanzenService($finanzenRepository);

        $userFilesService = new UserFilesService(User::USER);
        $investmentsRepository = new InvestmentsRepository($userFilesService);

        $this->logger = $logger;
        $this->investmentsService = new InvestmentsService($finanzenService, $investmentsRepository);
    }


    protected function run($arguments): void
    {
        $this->logger->info("HourlyInvestmentsAlarmJob started", ["app" => Application::APP_ID]);

        $this->investmentsService->...();
    }
}