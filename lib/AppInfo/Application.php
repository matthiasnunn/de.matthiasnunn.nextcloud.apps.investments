<?php

namespace OCA\Investments\AppInfo;

use OCA\Investments\Repositories\FinanzenNetRepository;
use OCA\Investments\Repositories\InvestmentsRepository;
use OCA\Investments\Services\FinanzenService;
use OCA\Investments\Services\InvestmentsDevelopmentService;
use OCA\Investments\Services\InvestmentsService;
use OCA\Investments\Services\MailService;
use OCA\Investments\Widgets\InvestmentsDevelopmentWidget;
use OCA\Shared\AppInfo\User;
use OCA\Shared\Services\UserFilesService;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCP\Mail\IMailer;
use Psr\Log\LoggerInterface;


class Application extends App implements IBootstrap
{
    public const APP_ID = "investments";


    public function __construct()
    {
        parent::__construct(self::APP_ID);
    }


    #[Override]
    public function boot(IBootContext $context): void
    {
    }


    #[Override]
    public function register(IRegistrationContext $context): void
    {
        $context->registerDashboardWidget(InvestmentsDevelopmentWidget::class);

        $context->registerService(InvestmentsService::class, function($c)
        {
            $finanzenRepository = new FinanzenNetRepository();
            $finanzenService = new FinanzenService($finanzenRepository);

            $logger = $c->get(LoggerInterface::class);

            $userFilesService = new UserFilesService($logger, User::USER);
            $investmentsRepository = new InvestmentsRepository($userFilesService);

            $mailService = new MailService($logger, $c->get(IMailer::class));

            return new InvestmentsService($finanzenService, $investmentsRepository, $mailService);
        });

        $context->registerService(InvestmentsDevelopmentService::class, function($c)
        {
            $logger = $c->get(LoggerInterface::class);

            $userFilesService = new UserFilesService($logger, User::USER);
            $investmentsRepository = new InvestmentsRepository($userFilesService);

            return new InvestmentsDevelopmentService($investmentsRepository, $c->get(InvestmentsService::class), $logger);
        });
    }
}