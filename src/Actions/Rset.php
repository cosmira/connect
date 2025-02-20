<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;

class Rset
{
    public function handle(SmtpSession $session): string
    {
        $session->reset();

        return Status::SUCCESS->response('Session reset');
    }
}
