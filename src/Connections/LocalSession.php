<?php

namespace Cosmira\Connect\Connections;

/**
 * Implements the Session interface for local testing purposes.
 * This class mimics the behavior of a real session but instead operates in-memory,
 * without establishing network connections or relying on external resources.
 */
class LocalSession implements Session
{
    use SessionData;

    /**
     * Mocks the response writing to the session.
     * In a real environment, this method would send data over a connection.
     * In this case, it simply returns the response for testing purposes.
     *
     * @param string|null $response The response message to "write".
     *
     * @return string|null The response message itself, for testing and verification.
     */
    public function write(?string $response = ''): ?string
    {
        return $response;
    }
}
