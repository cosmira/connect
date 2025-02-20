<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\Status;

/**
 * Обрабатывает команду NOOP.
 * Просто отвечает 250 OK.
 */
class Noop
{
    public function handle(): string
    {
        return Status::SUCCESS->response();
    }
}
