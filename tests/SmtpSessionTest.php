<?php

namespace Cosmira\Connect\Tests;

use Cosmira\Connect\Connections\LocalSession;
use PHPUnit\Framework\TestCase;

class SmtpSessionTest extends TestCase
{
    public function test_can_store_client_hostname()
    {
        $session = new LocalSession;
        $session->setClientHostname('example.com');

        $this->assertEquals('example.com', $session->getClientHostname());
    }

    public function test_can_store_sender_and_recipients()
    {
        $session = new LocalSession;
        $session->setSender('sender@example.com');
        $session->addRecipient('recipient@example.com');

        $this->assertEquals('sender@example.com', $session->getSender());
        $this->assertContains('recipient@example.com', $session->getRecipients());
    }

    public function test_can_store_message_lines()
    {
        $session = new LocalSession;
        $session->addMessageLine('Hello');
        $session->addMessageLine('World');

        $this->assertEquals("Hello\r\nWorld", $session->getMessage());
    }
}
