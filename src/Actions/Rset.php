<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\Connections\Session;
use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;

class Rset
{
    public function handle(Session $session): string
    {
        $session->reset();

        return Status::SUCCESS->response('Session reset');
    }
}
