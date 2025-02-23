<?php

namespace Esplora\Lumos\Connections;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait SessionData
{
    private ?string $clientHostname = null;
    private ?string $sender = null;
    private array $recipients = [];
    private bool $awaitingData = false;
    private array $messageLines = [];
    private bool $closed = false;

    /**
     * Sets the client's hostname for the session.
     *
     * @param string $hostname The hostname of the client.
     */
    public function setClientHostname(string $hostname): void
    {
        $this->clientHostname = $hostname;
    }

    /**
     * Retrieves the client's hostname.
     *
     * @return string|null The hostname of the client if set, or null if not set.
     */
    public function getClientHostname(): ?string
    {
        return $this->clientHostname;
    }

    /**
     * Sets the sender's email address for the session.
     *
     * @param string $email The sender's email.
     */
    public function setSender(string $email): void
    {
        $this->sender = $email;
    }

    /**
     * Retrieves the sender's email address.
     *
     * @return string|null The sender's email address if set, or null if not set.
     */
    public function getSender(): ?string
    {
        return $this->sender;
    }

    /**
     * Adds a recipient's email address to the session.
     *
     * @param string $email The recipient's email.
     */
    public function addRecipient(string $email): void
    {
        $this->recipients[] = $email;
    }

    /**
     * Retrieves the list of recipients for the session.
     *
     * @return string[] An array of recipient email addresses.
     */
    public function getRecipients(): array
    {
        return $this->recipients;
    }

    /**
     * Sets the awaiting data status for the session.
     *
     * @param bool $status True if awaiting data, false otherwise.
     */
    public function setAwaitingData(bool $status): void
    {
        $this->awaitingData = $status;
    }

    /**
     * Checks if the session is awaiting data.
     *
     * @return bool True if awaiting data, false otherwise.
     */
    public function isAwaitingData(): bool
    {
        return $this->awaitingData;
    }

    /**
     * Adds a line to the session message.
     *
     * @param string $line A line of message content to append.
     */
    public function addMessageLine(string $line): void
    {
        $this->messageLines[] = $line;
    }

    /**
     * Retrieves the full message composed of all lines.
     *
     * @return string The complete message formed from the added lines.
     */
    public function getMessage(): string
    {
        return implode("\r\n", $this->messageLines);
    }

    /**
     * Closes the session, preventing further changes.
     */
    public function close(): void
    {
        $this->closed = true;
    }

    /**
     * Checks if the session is closed.
     *
     * @return bool True if the session is closed, false otherwise.
     */
    public function isClosed(): bool
    {
        return $this->closed;
    }

    /**
     * Resets the session's state, clearing all data such as sender, recipients, and message.
     */
    public function reset(): void
    {
        $this->sender = null;
        $this->recipients = [];
        $this->messageLines = [];
        $this->awaitingData = false;
    }

    /**
     * @deprecated Не нужно формировать самому
     * Export the current session as an EML-formatted string.
     *
     * @return string
     */
    public function export(): string
    {
        return $this->getMessage();

        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
        ];

        if ($this->sender) {
            $headers[] = "From: {$this->sender}";
        }

        if (! empty($this->recipients)) {
            $headers[] = 'To: '.implode(', ', $this->recipients);
        }

        $headers[] = 'Date: '.Carbon::now()->toRfc2822String();

        // Generate a unique Message-ID using the client's hostname or fallback to localhost
        $domain = $this->clientHostname ?: 'localhost';
        $headers[] = 'Message-ID: <'.Str::uuid()."@{$domain}>";

        if ($this->clientHostname) {
            $headers[] = "X-Client-Hostname: {$this->clientHostname}";
        }

        // Формируем тело письма
        $body = $this->getMessage();

        // Возвращаем готовый EML
        return implode("\r\n", $headers)."\r\n\r\n".$body;
    }
}
