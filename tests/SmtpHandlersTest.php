<?php

namespace Esplora\Lumos\Tests;

use Esplora\Lumos\Actions\Data;
use Esplora\Lumos\Actions\Helo;
use Esplora\Lumos\Actions\Mail;
use Esplora\Lumos\Actions\Message;
use Esplora\Lumos\Actions\Quit;
use Esplora\Lumos\Actions\Rcpt;
use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;
use PHPUnit\Framework\TestCase;

class SmtpHandlersTest extends TestCase
{
    public function test_helo_command()
    {
        $session = new SmtpSession;
        $handler = new Helo;
        $response = $handler->handle($session, 'example.com');

        $this->assertStringContainsString('Hello example.com', $response);
    }

    public function test_mail_from_command()
    {
        $session = new SmtpSession;
        $handler = new Mail;
        $response = $handler->handle($session, '<sender@example.com>');

        $this->assertEquals(Status::SUCCESS->response(), $response);
        $this->assertEquals('sender@example.com', $session->getSender());
    }

    public function test_rcpt_to_command()
    {
        $session = new SmtpSession;
        $handler = new Rcpt;
        $response = $handler->handle($session, '<recipient@example.com>');

        $this->assertEquals(Status::SUCCESS->response(), $response);
        $this->assertContains('recipient@example.com', $session->getRecipients());
    }

    public function test_data_command()
    {
        $session = new SmtpSession;
        $handler = new Data;
        $response = $handler->handle($session, '');

        $this->assertEquals(Status::START_MAIL_INPUT->response(), $response);
        $this->assertTrue($session->isAwaitingData());
    }

    public function test_message_input()
    {
        $session = new SmtpSession;
        $dataHandler = new Data;
        $messageHandler = new Message;

        $dataHandler->handle($session, '');
        $messageHandler->handle($session, 'Hello, world!');
        $messageHandler->handle($session, 'This is a test.');
        $response = $messageHandler->handle($session, '.');

        $this->assertEquals(Status::SUCCESS->response('Message received'), $response);
        $this->assertEquals("Hello, world!\nThis is a test.", $session->getMessage());
    }

    public function test_quit_command()
    {
        $session = new SmtpSession;
        $handler = new Quit;
        $response = $handler->handle($session);

        $this->assertEquals(Status::SERVICE_CLOSING->response(), $response);
        $this->assertTrue($session->isClosed());
    }
}
