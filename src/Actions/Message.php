<?php

namespace Esplora\Lumos\Actions;

use Esplora\Lumos\Connections\Session;
use Esplora\Lumos\Status;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Обрабатывает SMTP команду MESSAGE.
 * Ожидает данные от клиента и сохраняет сообщение.
 */
class Message
{
    public function handle(Session $session, string $line): string
    {
        if (! $session->isAwaitingData()) {
            return Status::BAD_SEQUENCE->response();
        }

        // Завершение ввода письма
        if (Str::endsWith($line, ".\r\n")) {
            $session->addMessageLine(Str::beforeLast($line, ".\r\n"));
            $session->setAwaitingData(false);

            // TODO: пока просто сохраняем в файл
            file_put_contents($this->generateFileName(), $session->getMessage());

            return Status::SUCCESS->response('Message received');
        }

        $session->addMessageLine($line);

        return Status::SUCCESS->response();
    }

    /**
     * Генерирует уникальное имя файла для хранения письма.
     */
    private function generateFileName(): string
    {
        return sprintf(
            '%s/../../tmp/%s.eml',
            __DIR__,
            Carbon::now()->format('Y-m-d\TH-i-s.u\Z')
        );
    }
}
