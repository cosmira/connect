<?php

namespace Cosmira\Connect;

use Cosmira\Connect\Actions\Data;
use Cosmira\Connect\Actions\Helo;
use Cosmira\Connect\Actions\Mail;
use Cosmira\Connect\Actions\Message;
use Cosmira\Connect\Actions\Quit;
use Cosmira\Connect\Actions\Rcpt;
use Cosmira\Connect\Connections\Session;
use Illuminate\Support\Str;

class Server
{
    /**
     * Массив с командами и их обработчиками.
     *
     * @var array
     */
    public array $commands = [
        'HELO'    => Helo::class,
        'MAIL'    => Mail::class,
        'RCPT'    => Rcpt::class,
        'DATA'    => Data::class,
        'MESSAGE' => Message::class,
        'QUIT'    => Quit::class,
    ];

    /**
     * @param \Cosmira\Connect\Connections\Session $session
     */
    public function __construct(protected Session $session) {}

    /**
     * Обработка команды.
     *
     * @param string $request
     *
     * @return string
     */
    public function handle(string $request): string
    {
        [$command, $arg] = $this->parseRequest($request);

        // Проверяем наличие команды в списке допустимых
        if (! $this->isValidCommand($command)) {
            $error = Status::COMMAND_UNRECOGNIZED->response();

            $this->session->write($error);

            return $error;
        }

        // Получаем класс обработчика для команды
        $handlerClass = $this->commands[strtoupper($command)];

        // Создаем экземпляр обработчика. TODO: Разрешаем обработчик через контейнер
        $handler = new $handlerClass;

        // Обрабатываем команду и получаем ответ
        $response = $handler->handle($this->session, $arg);

        $this->session->write($response);

        return $response;
    }

    /**
     * Проверка на допустимость команды.
     *
     * @param string $command
     *
     * @return bool
     */
    protected function isValidCommand(string $command): bool
    {
        return isset($this->commands[$command]);
    }

    /**
     * @param string $request
     *
     * @return string[]
     */
    protected function parseRequest(string $request): array
    {
        // Если ожидается передача данных, считаем запрос как аргумент для MESSAGE
        if ($this->session->isAwaitingData()) {
            return ['MESSAGE', $request];
        }

        // Парсим команду и аргумент
        return [
            Str::of($request)->before(' ')->trim()->upper()->toString(),
            Str::of($request)->after(' ')->trim()->toString(),
        ];
    }
}
