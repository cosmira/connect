<?php

namespace Cosmira\Connect\Actions;

use Cosmira\Connect\Connections\Session;
use Cosmira\Connect\Status;

class Rset
{
    public function handle(Session $session): string
    {
        $session->reset();

        return Status::SUCCESS->response('Session reset');
    }
}
