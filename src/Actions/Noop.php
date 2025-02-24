<?php

namespace Cosmira\Connect\Actions;

use Cosmira\Connect\Status;

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
