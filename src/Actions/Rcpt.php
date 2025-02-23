<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\Connections\Session;
use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;
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
