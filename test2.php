<?php

include_once 'vendor/autoload.php';

$message = \Esplora\Lumos\MailParser::fromString(file_get_contents('m0015.txt'), false);

dd(
    $message->html(),
    $message->text(),
    $message->attachments()->all(),
    $message->cc()
);

// Тестирование
$parser = new \Esplora\Lumos\EmailParser(file_get_contents('m0015.txt'));

echo '📩 Тема: '.($parser->headers()->get('subject') ?? 'Без темы').PHP_EOL;
echo "📜 Тело письма:\n".$parser->body()->content().PHP_EOL;

dd($parser->attachments());

foreach ($parser->attachments() as $attachment) {
    // file_put_contents($attachment->filename(), $attachment->content());
    echo '📎 Сохранен файл: '.$attachment->filename().PHP_EOL;
}
