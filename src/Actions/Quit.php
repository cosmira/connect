<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\Connections\Session;
use Esplora\Lumos\Status;

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
