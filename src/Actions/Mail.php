<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;
use Illuminate\Support\Str;

/**
 * Обрабатывает команду MAIL FROM.
 * Устанавливает отправителя письма.
 */
class Mail
{
    public function handle(SmtpSession $session, string $argument): string
    {
        $email = Str::of($argument)->between('<', '>')->toString();

        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Status::PARAMETERS_ERROR->response('MAIL FROM:<address>');
        }

        $session->setSender($email);

        return Status::SUCCESS->response();
    }
}
