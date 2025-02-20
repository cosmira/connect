<?php

namespace Esplora\Lumos\Tests;

use Esplora\Lumos\Server;
use Esplora\Lumos\SmtpSession;
use Esplora\Lumos\Status;
use PHPUnit\Framework\TestCase;

class SmtpServerTest extends TestCase
{
    /**
     * Проверка полного цикла работы SMTP сервера
     */
    public function test_smtp_server():void
    {
        // Создаем сессию и сервер
        $session = new SmtpSession;
        $server = new Server($session);

        // Проверка команды HELO
        $response = $server->handle('HELO example.com');
        $this->assertEquals('250 OK Hello example.com', $response);

        // Проверка команды MAIL
        $response = $server->handle('MAIL FROM:<sender@example.com>');
        $this->assertEquals('250 OK', $response);

        // Проверка команды RCPT
        $response = $server->handle('RCPT TO:<recipient@example.com>');
        $this->assertEquals('250 OK', $response);

        // Проверка команды DATA
        $response = $server->handle('DATA');
        $this->assertEquals('354 Start mail input; end with <CRLF>.<CRLF>', $response);

        // Проверка команды QUIT
        $response = $server->handle('QUIT');
        $this->assertEquals('221 Bye', $response);
    }

    /**
     * Проверка состояния сессии после выполнения команд
     */
    public function test_smtp_server_session_state()
    {
        // Создаем сессию и сервер
        $session = new SmtpSession;
        $server = new Server($session);

        // Выполняем команды
        $server->handle('HELO example.com');
        $server->handle('MAIL FROM:<sender@example.com>');
        $server->handle('RCPT TO:<recipient@example.com>');
        $server->handle('DATA');

        // Проверяем, что сессия ожидает данные
        $this->assertTrue($session->isAwaitingData());

        // Завершаем сессию
        $server->handle('QUIT');

        // Проверяем, что сессия закрыта
        $this->assertTrue($session->isClosed());
    }

    /**
     * Тестирует полный цикл SMTP-общения с отправкой реального письма.
     */
    public function test_full_email_message()
    {
        // Создаем сессию и сервер
        $session = new SmtpSession();
        $server = new Server($session);

        // 1. Представление клиента (HELO)
        $response = $server->handle('HELO example.com');
        $this->assertEquals("250 OK Hello example.com", $response);
        $this->assertEquals("example.com", $session->getClientHostname());

        // 2. Установка отправителя (MAIL FROM)
        $response = $server->handle('MAIL FROM:<sender@example.com>');
        $this->assertEquals("250 OK", $response);
        $this->assertEquals("sender@example.com", $session->getSender());

        // 3. Добавление получателя (RCPT TO)
        $response = $server->handle('RCPT TO:<recipient@example.com>');
        $this->assertEquals("250 OK", $response);
        $this->assertContains("recipient@example.com", $session->getRecipients());

        // 4. Начало ввода данных письма (DATA)
        $response = $server->handle('DATA');
        $this->assertEquals("354 Start mail input; end with <CRLF>.<CRLF>", $response);
        $this->assertTrue($session->isAwaitingData());

        // 5. Отправляем тело письма через команду MESSAGE.
        // Предполагаем, что каждый вызов MESSAGE добавляет строку к телу письма.
        $response = $server->handle('MESSAGE Subject: Test Email');
        $this->assertEquals("250 OK", $response);

        // Пустая строка между заголовком и телом
        $response = $server->handle('MESSAGE ');
        $this->assertEquals("250 OK", $response);

        // Тело письма
        $response = $server->handle('MESSAGE This is the body of the email.');
        $this->assertEquals("250 OK", $response);

        // Завершаем ввод письма точкой
        $response = $server->handle('MESSAGE .');
        $this->assertEquals(Status::SUCCESS->response('Message received'), $response);
        $this->assertFalse($session->isAwaitingData());

        // 6. Завершаем сессию (QUIT)
        $response = $server->handle('QUIT');
        $this->assertEquals("221 Bye", $response);
        $this->assertTrue($session->isClosed());

        // 7. Проверяем, что сообщение корректно сохранено в сессии.
        $expectedMessage = "Subject: Test Email\n\nThis is the body of the email.";
        $this->assertEquals($expectedMessage, $session->getMessage());
    }
}
