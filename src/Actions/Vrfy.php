<?php

namespace Cosmira\Connect\Actions;

use Cosmira\Connect\Status;

/**
 * Обрабатывает команду VRFY.
 * Проверяет существование почтового адреса.
 */
class Vrfy
{
    public function handle(string $argument): string
    {
        if (empty($argument)) {
            return Status::PARAMETERS_ERROR->response('VRFY <address>');
        }

        return Status::SUCCESS->response('User exists');
    }
}
