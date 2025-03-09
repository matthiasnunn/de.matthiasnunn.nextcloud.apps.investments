

use OCP\Mail\IMailer;

class MailService {
    private IMailer $mailer;

    public function __construct(IMailer $mailer) {
        $this->mailer = $mailer;
    }

TODO: Mail oder Mailer???

use OCP\Mail\IMailer;
use OCP\Mail\IMessage;
use OCP\IUserManager;


class MailService {
    private IMailer $mailer;

    public function __construct(IMailer $mailer) {
        $this->mailer = $mailer;
    }

    public function sendMail(string $to, string $subject, string $body) {
        $message = $this->mailer->createMessage();
        $message->setTo([$to => 'Empfänger'])
                ->setSubject($subject)
                ->setPlainBody($body);

        try {
            $this->mailer->send($message);
        } catch (\Exception $e) {
            \OC::$server->getLogger()->error("Mailversand fehlgeschlagen: " . $e->getMessage());
        }
    }
}