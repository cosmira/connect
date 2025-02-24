<?php

namespace Cosmira\Connect\Connections;

/**
 * Defines a session interface for handling communication between a client and a server.
 * This session maintains information about the client, sender, recipients, message content,
 * and session state (e.g., awaiting data or closed).
 */
interface Session
{
    /**
     * Sets the client's hostname.
     *
     * @param string $hostname The hostname of the client initiating the session.
     */
    public function setClientHostname(string $hostname): void;

    /**
     * Retrieves the client's hostname.
     *
     * @return string|null The client hostname if set, or null otherwise.
     */
    public function getClientHostname(): ?string;

    /**
     * Sets the sender's email address.
     *
     * @param string $email The sender's email.
     */
    public function setSender(string $email): void;

    /**
     * Retrieves the sender's email address.
     *
     * @return string|null The sender's email if set, or null otherwise.
     */
    public function getSender(): ?string;

    /**
     * Adds a recipient's email address.
     *
     * @param string $email The recipient's email.
     */
    public function addRecipient(string $email): void;

    /**
     * Retrieves the list of recipients.
     *
     * @return string[] An array of recipient email addresses.
     */
    public function getRecipients(): array;

    /**
     * Sets the session's awaiting data status.
     *
     * @param bool $status True if the session is awaiting data, false otherwise.
     */
    public function setAwaitingData(bool $status): void;

    /**
     * Checks if the session is awaiting data.
     *
     * @return bool True if awaiting data, false otherwise.
     */
    public function isAwaitingData(): bool;

    /**
     * Appends a line to the session message.
     *
     * @param string $line A single line of message content.
     */
    public function addMessageLine(string $line): void;

    /**
     * Retrieves the full message content.
     *
     * @return string The complete message composed from the added lines.
     */
    public function getMessage(): string;

    /**
     * Closes the session, preventing further modifications.
     */
    public function close(): void;

    /**
     * Checks if the session is closed.
     *
     * @return bool True if the session is closed, false otherwise.
     */
    public function isClosed(): bool;

    /**
     * Resets the session state, clearing all stored data.
     * This should remove the sender, recipients, message content,
     * and reset the session status.
     */
    public function reset(): void;

    /**
     * Processes the response received from the server.
     *
     * This method can be used to handle the response action and update the session state based on it.
     *
     * @param string|null $response The response message to be processed.
     *
     * @return mixed The processed response or any other data.
     */
    public function write(?string $response = ''): mixed;

    /**
     * Export the current session as an EML-formatted string.
     *
     * @return string
     */
    public function export(): string;
}
