<?php

namespace OCA\Investments\Services;

use OCP\ILogger;
use OCP\Mail\IMailer;


class MailService
{
    private ILogger $logger;
    private IMailer $mailer;

    public function __construct(ILogger $logger, IMailer $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public function sendMail(string $to, string $subject, string $body): void
    {
        $message = $this->mailer->createMessage();
        $message->setTo([$to])
                ->setSubject($subject)
                ->setPlainBody($body);

        try
        {
            $this->mailer->send($message);
        }
        catch (\Exception $e)
        {
            $this->logger->error("Mailversand fehlgeschlagen: " . $e->getMessage());
        }
    }
}