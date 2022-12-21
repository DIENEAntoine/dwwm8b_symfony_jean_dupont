<?php
namespace App\Service;

use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

    class SendEmailService
    {

        private MailerInterface $mailer;

        public function __construct(MailerInterface $mailer)
        {
            $this->mailer = $mailer;
        }

        /**
         * Cette methode permet d'envoyer l'email
         *
         * @param array $data
         * @return void
         */
        public function send(array $data)
        {
            $sender_email = $data['sender_email'];
            $sender_name = $data['sender_name'];
            $recipient_email = $data['recipient_email'];
            $subject = $data['subject'];
            $html_template = $data['html_template'];
            $context = $data['context'];

            $email = new TemplatedEmail();

            $email->from(new Address($sender_email,$sender_name))
                ->to($recipient_email)
                ->subject($subject)
                ->htmlTemplate($html_template)
                ->context($context)
            ;

            try 
            {
                $this->mailer->send($email);
            }
            catch (TransportExceptionInterface $te) 
            {
                throw $te;
            }


        }
    }