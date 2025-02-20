<?php

namespace Esplora\Lumos;

use Esplora\Lumos\Actions\Data;
use Esplora\Lumos\Actions\Helo;
use Esplora\Lumos\Actions\Mail;
use Esplora\Lumos\Actions\Message;
use Esplora\Lumos\Actions\Quit;
use Esplora\Lumos\Actions\Rcpt;
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
     * @param \Esplora\Lumos\SmtpSession $session
     */
    public function __construct(protected SmtpSession $session) {}

    /**
     * Обработка команды.
     *
     * @param string $request
     *
     * @return string
     */
    public function handle(string $request): string
    {
        // Разбиваем запрос на команду и аргументы
        $command = Str::of($request)->before(' ')->upper();
        $arg = Str::of($request)->after(' ')->trim();

        // Проверяем наличие команды в списке допустимых
        if (! isset($this->commands[strtoupper($command)])) {
            return '500 Command not recognized';
        }

        // Получаем класс обработчика для команды
        $handlerClass = $this->commands[strtoupper($command)];

        // Создаем экземпляр обработчика и передаем команду и аргументы
        $handler = new $handlerClass;

        // Выполняем обработку команды и возвращаем результат
        return $handler->handle($this->session, $arg);
    }
}
