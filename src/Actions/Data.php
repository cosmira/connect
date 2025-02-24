<?php

namespace Cosmira\Connect\Actions;

use Cosmira\Connect\Connections\Session;
use Cosmira\Connect\Status;

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
