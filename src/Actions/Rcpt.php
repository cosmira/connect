<?php

namespace Cosmira\Connect\Actions;

use Cosmira\Connect\Connections\Session;
use Cosmira\Connect\Status;
use Illuminate\Support\Str;

/**
 * Обрабатывает команду RCPT TO.
 * Добавляет получателя в список получателей письма.
 */
class Rcpt
{
    public function handle(Session $session, string $argument): string
    {
        $email = Str::of($argument)->between('<', '>')->toString();

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Status::PARAMETERS_ERROR->response('MAIL FROM:<address>');
        }

        $session->addRecipient($email);

        return Status::SUCCESS->response();
    }
}
