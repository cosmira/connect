<?php

namespace Cosmira\Connect\Tests;

use Cosmira\Connect\Actions\Data;
use Cosmira\Connect\Actions\Helo;
use Cosmira\Connect\Actions\Mail;
use Cosmira\Connect\Actions\Message;
use Cosmira\Connect\Actions\Quit;
use Cosmira\Connect\Actions\Rcpt;
use Cosmira\Connect\Connections\LocalSession;
use Cosmira\Connect\Status;
use PHPUnit\Framework\TestCase;

class SmtpHandlersTest extends TestCase
{
    public function test_helo_command()
    {
        $session = new LocalSession;
        $handler = new Helo;
        $response = $handler->handle($session, 'example.com');

        $this->assertStringContainsString('Hello example.com', $response);
    }

    public function test_mail_from_command()
    {
        $session = new LocalSession;
        $handler = new Mail;
        $response = $handler->handle($session, '<sender@example.com>');

        $this->assertEquals(Status::SUCCESS->response(), $response);
        $this->assertEquals('sender@example.com', $session->getSender());
    }

    public function test_rcpt_to_command()
    {
        $session = new LocalSession;
        $handler = new Rcpt;
        $response = $handler->handle($session, '<recipient@example.com>');

        $this->assertEquals(Status::SUCCESS->response(), $response);
        $this->assertContains('recipient@example.com', $session->getRecipients());
    }

    public function test_data_command()
    {
        $session = new LocalSession;
        $handler = new Data;
        $response = $handler->handle($session, '');

        $this->assertEquals(Status::START_MAIL_INPUT->response(), $response);
        $this->assertTrue($session->isAwaitingData());
    }

    public function test_message_input()
    {
        $session = new LocalSession;
        $dataHandler = new Data;
        $messageHandler = new Message;

        $dataHandler->handle($session, '');
        $messageHandler->handle($session, 'Hello, world!');
        $messageHandler->handle($session, 'This is a test.');
        $response = $messageHandler->handle($session, ".\r\n");

        $this->assertEquals(Status::SUCCESS->response('Message received'), $response);
        $this->assertEquals("Hello, world!\r\nThis is a test.\r\n", $session->getMessage());
    }

    public function test_quit_command()
    {
        $session = new LocalSession;
        $handler = new Quit;
        $response = $handler->handle($session);

        $this->assertEquals(Status::SERVICE_CLOSING->response(), $response);
        $this->assertTrue($session->isClosed());
    }
}
