<?php

namespace Cosmira\Connect\Actions;

use Cosmira\Connect\Connections\Session;
use Cosmira\Connect\Status;

/**
 * Обрабатывает команду QUIT.
 * Завершает соединение с клиентом.
 */
class Quit
{
    public function handle(Session $session): string
    {
        $session->close();

        return Status::SERVICE_CLOSING->response();
    }
}
