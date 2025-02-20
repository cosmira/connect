<?php

namespace Esplora\Lumos\Tests;

use Esplora\Lumos\SmtpSession;
use PHPUnit\Framework\TestCase;

class SmtpSessionTest extends TestCase
{
    public function test_can_store_client_hostname()
    {
        $session = new SmtpSession;
        $session->setClientHostname('example.com');

        $this->assertEquals('example.com', $session->getClientHostname());
    }

    public function test_can_store_sender_and_recipients()
    {
        $session = new SmtpSession;
        $session->setSender('sender@example.com');
        $session->addRecipient('recipient@example.com');

        $this->assertEquals('sender@example.com', $session->getSender());
        $this->assertContains('recipient@example.com', $session->getRecipients());
    }

    public function test_can_store_message_lines()
    {
        $session = new SmtpSession;
        $session->addMessageLine('Hello');
        $session->addMessageLine('World');

        $this->assertEquals("Hello\nWorld", $session->getMessage());
    }
}
