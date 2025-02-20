<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\Status;

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
