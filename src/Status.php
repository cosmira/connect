<?php

namespace Cosmira\Connect;

/**
 * Перечисление (Enum) SMTP-ответов сервера
 */
enum Status: string
{
    /**
     * Запрошенное действие выполнено успешно.
     */
    case SUCCESS = '250';              // Запрошенное действие выполнено успешно.
    case START_MAIL_INPUT = '354';       // Начало ввода письма; завершите ввод <CRLF>.<CRLF>.
    case SERVICE_CLOSING = '221';        // Завершение сеанса, закрытие соединения.

    /**
     * Сервис временно недоступен, соединение закрывается.
     */
    case TEMP_SERVICE_UNAVAILABLE = '421'; // Сервис временно недоступен, соединение закрывается.
    case MAILBOX_BUSY = '450';             // Запрошенное действие не выполнено: почтовый ящик занят.
    case PROCESSING_ERROR = '451';         // Запрошенное действие прервано: локальная ошибка при обработке.
    case INSUFFICIENT_STORAGE = '452';     // Запрошенное действие не выполнено: недостаточно системных ресурсов.

    /**
     * Синтаксическая ошибка, команда не распознана.
     */
    case COMMAND_UNRECOGNIZED = '500';     // Синтаксическая ошибка, команда не распознана.
    case PARAMETERS_ERROR = '501';         // Синтаксическая ошибка в параметрах команды.
    case COMMAND_NOT_IMPLEMENTED = '502';  // Команда не реализована.
    case BAD_SEQUENCE = '503';             // Неверная последовательность команд.
    case COMMAND_NOT_SUPPORTED = '504';    // Параметр команды не поддерживается.
    case MAILBOX_UNAVAILABLE = '550';      // Запрошенное действие не выполнено: почтовый ящик недоступен.
    case USER_NOT_LOCAL = '551';           // Пользователь не является локальным; рекомендуется использовать переадресацию.
    case ACTION_ABORTED = '552';           // Запрошенное действие прервано: превышена квота хранения.
    case MAILBOX_NAME_ERROR = '553';       // Запрошенное действие не выполнено: недопустимое имя почтового ящика.
    case TRANSACTION_FAILED = '554';       // Транзакция не выполнена.

    /**
     * Дополнительные статусы (расширения)
     */
    case AUTH_REQUIRED = '530';            // Требуется аутентификация.
    case AUTH_FAILED = '535';              // Ошибка аутентификации: неверные учетные данные.

    /**
     * Получить текстовое описание статуса
     *
     * @return string Описание статуса.
     */
    public function message(): string
    {
        return match ($this) {
            self::SUCCESS                  => 'OK',
            self::START_MAIL_INPUT         => 'Start mail input; end with <CRLF>.<CRLF>',
            self::SERVICE_CLOSING          => 'Bye',
            self::TEMP_SERVICE_UNAVAILABLE => 'Service not available',
            self::MAILBOX_BUSY             => 'Requested mail action not taken: mailbox busy',
            self::PROCESSING_ERROR         => 'Requested action aborted: local error in processing',
            self::INSUFFICIENT_STORAGE     => 'Insufficient system storage',
            self::COMMAND_UNRECOGNIZED     => 'Syntax error, command unrecognized',
            self::PARAMETERS_ERROR         => 'Syntax error in parameters',
            self::COMMAND_NOT_IMPLEMENTED  => 'Command not implemented',
            self::BAD_SEQUENCE             => 'Bad sequence of commands',
            self::COMMAND_NOT_SUPPORTED    => 'Command parameter not implemented',
            self::MAILBOX_UNAVAILABLE      => 'Requested action not taken: mailbox unavailable',
            self::USER_NOT_LOCAL           => 'User not local; try a different path',
            self::ACTION_ABORTED           => 'Requested mail action aborted: exceeded storage allocation',
            self::MAILBOX_NAME_ERROR       => 'Requested action not taken: mailbox name not allowed',
            self::TRANSACTION_FAILED       => 'Transaction failed',
            self::AUTH_REQUIRED            => 'Authentication required',
            self::AUTH_FAILED              => 'Authentication failed',
        };
    }

    /**
     * Получить полный ответ сервера (код + описание)
     *
     * @param string $details Дополнительные детали для ответа.
     *
     * @return string Полный ответ сервера.
     */
    public function response(string $details = ''): string
    {
        return $this->value.' '.$this->message().($details ? " $details" : '');
    }
}
