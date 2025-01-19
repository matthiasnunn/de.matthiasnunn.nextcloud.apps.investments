<?php

namespace OCA\Investments\AppInfo;

use OCP\AppFramework\App;


class Application extends App
{
    public const APP_ID = "investments";

    public function __construct()
    {
        parent::__construct(self::APP_ID);
    }
}