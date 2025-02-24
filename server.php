<?php

require 'vendor/autoload.php';

use Cosmira\Connect\Connections\ReactSession;
use Cosmira\Connect\Server;
use React\EventLoop\Loop;
use React\Socket\ConnectionInterface;
use React\Socket\SocketServer;

$loop = Loop::get();
$socket = new SocketServer('0.0.0.0:2525', [], $loop);

echo "SMTP-сервер запущен на 0.0.0.0:2525\r\n";

$socket->on('connection', function (ConnectionInterface $connection) {

    // Создаем сессию и сервер
    $session = new ReactSession($connection);
    $server = new Server($session);

    // todo need save?
    dump('Новое соединение: '.$connection->getRemoteAddress());

    $connection->write("220 Simple ReactPHP SMTP Server\r\n");

    $connection->on('data', fn (string $data) => $server->handle($data));
    $connection->on('close', fn () => dump('Соединение закрыто'));
});

$loop->run();
