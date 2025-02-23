<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\Connections\Session;
use Esplora\Lumos\Status;

/**
 * Обрабатывает команду DATA.
 * Начинает передачу тела письма.
 */
class Data
{
    public function handle(Session $session, string $argument): string
    {
        $session->setAwaitingData(true);

        return Status::START_MAIL_INPUT->response();
    }
}
