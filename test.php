<?php

include_once 'vendor/autoload.php';

$email = new \Esplora\Lumos\RawEmailFromFile('m0015.txt');
$parser = new \Esplora\Lumos\EmailParser($email);

dd(
    $parser->attachments()->all()
);

echo '📩 Тема: '.($parser->headers()->get('subject') ?? 'Без темы').PHP_EOL;

echo "📜 Тело письма:\n".$parser->body()->text().PHP_EOL;

foreach ($parser->attachments()->all() as $attachment) {
    file_put_contents($attachment['name'], $attachment['content']);
    echo '📎 Сохранен файл: '.$attachment['name'].PHP_EOL;
}
