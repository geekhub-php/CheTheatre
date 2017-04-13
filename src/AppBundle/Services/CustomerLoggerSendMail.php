<?php

namespace AppBundle\Services;


class CustomerLoggerSendMail
{
    protected $logger;


    public function __construct($logger, $mailer) {
        $this->logger = $logger;
        $this->logger->info('Log this!');

        $this->mailer = $mailer;
        $message = \Swift_Message::newInstance()
            ->setSubject('Hello Email')
            ->setFrom('bbbbla.com')
            ->setTo('blla.net')
            ->setBody('error!!!', 'text/html');
        $this->mailer->send($message);
    }

}