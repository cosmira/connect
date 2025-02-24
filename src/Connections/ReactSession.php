<?php

namespace Cosmira\Connect\Connections;

use React\Socket\ConnectionInterface;

/**
 * Implements the Session interface for managing client-server interactions over ReactPHP.
 * This class handles client hostname, sender, recipients, message content, and session state.
 * It also manages response writing to the connection.
 */
class ReactSession implements Session
{
    use SessionData;

    public function __construct(protected ConnectionInterface $connection) {}

    /**
     * Mocks the response writing to the session.
     * In a real environment, this method would send data over a connection.
     * In this case, it writes the response to the actual connection.
     *
     * @param string|null $response The response message to "write".
     *
     * @return mixed The result of writing the response to the connection.
     */
    public function write(?string $response = ''): mixed
    {
        if ($this->isClosed()) {
            $this->connection->end($response."\r\n");
        }

        return $this->connection->write($response."\r\n");
    }
}
