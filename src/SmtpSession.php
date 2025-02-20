<?php

namespace Esplora\Lumos;

/**
 * Управляет текущей SMTP-сессией клиента.
 */
class SmtpSession
{
    private ?string $clientHostname = null;
    private ?string $sender = null;
    private array $recipients = [];
    private bool $awaitingData = false;
    private array $messageLines = [];
    private bool $closed = false;

    public function setClientHostname(string $hostname): void
    {
        $this->clientHostname = $hostname;
    }

    public function getClientHostname(): ?string
    {
        return $this->clientHostname;
    }

    public function setSender(string $email): void
    {
        $this->sender = $email;
    }

    public function getSender(): ?string
    {
        return $this->sender;
    }

    public function addRecipient(string $email): void
    {
        $this->recipients[] = $email;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function setAwaitingData(bool $status): void
    {
        $this->awaitingData = $status;
    }

    public function isAwaitingData(): bool
    {
        return $this->awaitingData;
    }

    public function addMessageLine(string $line): void
    {
        $this->messageLines[] = $line;
    }

    public function getMessage(): string
    {
        return implode("\n", $this->messageLines);
    }

    public function close(): void
    {
        $this->closed = true;
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function reset(): void
    {
        $this->sender = null;
        $this->recipients = [];
        $this->messageLines = [];
        $this->awaitingData = false;
        $this->closed = false;
    }
}
