<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailService
{
    private MailerInterface $mailer;
    private string $senderAddress;

    // Injecter l'adresse d'expédition via le constructeur
    public function __construct(MailerInterface $mailer, string $senderAddress)
    {
        $this->mailer = $mailer;
        $this->senderAddress = $senderAddress;
    }

    public function sendEmail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from($this->senderAddress) // Utiliser l'adresse injectée
            ->to($to)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }
}