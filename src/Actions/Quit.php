<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;

/**
 * Обрабатывает команду QUIT.
 * Завершает соединение с клиентом.
 */
class Quit
{
    public function handle(SmtpSession $session): string
    {
        $session->close();

        return Status::SERVICE_CLOSING->response();
    }
}
