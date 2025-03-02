<?php

namespace OCA\Investments\AppInfo;

use OCA\Investments\Widgets\InvestmentsDevelopmentWidget;
use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;


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
    }
}