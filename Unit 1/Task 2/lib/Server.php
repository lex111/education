<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Education\Unit1\Task2;

class Server {
    /**
     * @var Connection $connection
     */
    private $connection;

    /**
     * @var resource $socket
     */
    private $socket;

    /**
     * @var callable $handler
     */
    private $handler;

    /**
     * Server constructor.
     *
     * @param string $ip
     * @param integer $port
     */
    public function __construct($ip, $port) {
        $this->connection = new Connection($ip, $port);
        $this->socket = $this->connection->createSocket();
    }

    /**
     * @param callable $handler
     */
    public function registerHandler(callable $handler) {
        $this->handler = $handler;
    }

    /**
     * @return void
     */
    public function listen() {
        while ($socket = socket_accept($this->socket)) {
            $connection = new Connection();
            $connection->setSocket($socket);

            call_user_func($this->handler, $connection);
        }
    }

    /**
     * @return void
     */
    public function close() {
        $this->connection->close();
    }
}
