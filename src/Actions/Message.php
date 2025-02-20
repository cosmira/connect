<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;

class Message
{
    public function handle(SmtpSession $session, string $line): string
    {
        if (! $session->isAwaitingData()) {
            return Status::BAD_SEQUENCE->response();
        }

        if ($line === '.') { // Завершение ввода письма
            $session->setAwaitingData(false);

            return Status::SUCCESS->response('Message received');
        }

        $session->addMessageLine($line);

        return Status::SUCCESS->response();
    }
}
