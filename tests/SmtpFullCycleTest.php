<?php

namespace Esplora\Lumos\Tests;

use Esplora\Lumos\Actions\Data;
use Esplora\Lumos\Actions\Helo;
use Esplora\Lumos\Actions\Mail;
use Esplora\Lumos\Actions\Message;
use Esplora\Lumos\Actions\Quit;
use Esplora\Lumos\Actions\Rcpt;
use Esplora\Lumos\Connections\LocalSession;
use Esplora\Lumos\Status;
use PHPUnit\Framework\TestCase;

/**
 * Проверяет полный цикл SMTP-общения.
 */
class SmtpFullCycleTest extends TestCase
{
    public function test_smtp_full_cycle()
    {
        $session = new LocalSession;

        // 1️⃣ HELO
        $heloHandler = new Helo;
        $response = $heloHandler->handle($session, 'example.com');
        $this->assertEquals(Status::SUCCESS->response('Hello example.com'), $response);
        $this->assertEquals('example.com', $session->getClientHostname());

        // 2️⃣ MAIL FROM
        $mailHandler = new Mail;
        $response = $mailHandler->handle($session, '<sender@example.com>');
        $this->assertEquals(Status::SUCCESS->response(), $response);
        $this->assertEquals('sender@example.com', $session->getSender());

        // 3️⃣ RCPT TO
        $rcptHandler = new Rcpt;
        $response = $rcptHandler->handle($session, '<recipient@example.com>');
        $this->assertEquals(Status::SUCCESS->response(), $response);
        $this->assertContains('recipient@example.com', $session->getRecipients());

        // 4️⃣ DATA (начинаем передачу сообщения)
        $dataHandler = new Data;
        $response = $dataHandler->handle($session, '');
        $this->assertEquals(Status::START_MAIL_INPUT->response(), $response);
        $this->assertTrue($session->isAwaitingData());

        // 5️⃣ Вводим текст сообщения
        $messageHandler = new Message;
        $messageHandler->handle($session, 'Hello, world!');
        $messageHandler->handle($session, 'This is a test.');
        $response = $messageHandler->handle($session, ".\r\n"); // Завершаем сообщение

        $this->assertEquals(Status::SUCCESS->response('Message received'), $response);
        $this->assertEquals("Hello, world!\r\nThis is a test.\r\n", $session->getMessage());

        // 6️⃣ QUIT (закрываем соединение)
        $quitHandler = new Quit;
        $response = $quitHandler->handle($session, '');

        $this->assertEquals(Status::SERVICE_CLOSING->response(), $response);
        $this->assertTrue($session->isClosed());
    }
}
