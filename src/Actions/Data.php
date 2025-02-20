<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;

/**
 * Обрабатывает команду DATA.
 * Начинает передачу тела письма.
 */
class Data
{
    public function handle(SmtpSession $session, string $argument): string
    {
        /*
        if (! empty($argument)) {
            return Status::START_MAIL_INPUT->response();
            //  return Status::PARAMETERS_ERROR->response('DATA');
        }
        */

        $session->setAwaitingData(true);

        return Status::START_MAIL_INPUT->response();
    }
}
