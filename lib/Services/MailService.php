<?php

namespace OCA\Investments\Services;

use OCP\Mail\IMailer;
use Psr\Log\LoggerInterface;


class MailService
{
    private LoggerInterface $logger;
    private IMailer $mailer;

    public function __construct(LoggerInterface $logger, IMailer $mailer)
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