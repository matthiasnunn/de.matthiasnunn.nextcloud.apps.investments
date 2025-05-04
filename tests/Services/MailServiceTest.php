<?php

namespace OCA\Investments\Tests\Services;

require_once "/var/www/html/lib/base.php";

use OCA\Investments\Services\MailService;
use OCP\Mail\IMailer;
use Psr\Log\LoggerInterface;


class MailServiceTest
{
    private MailService $mailService;


    public function __construct()
    {
        $this->mailService = new MailService(\OC::$server->get(LoggerInterface::class), \OC::$server->get(IMailer::class));

        $this->sendMail();
    }


    private function sendMail()
    {
        $this->mailService->sendMail(to: "matthias-nunn@gmx.de", subject: "Test", body: "Test");
    }
}


new MailServiceTest();