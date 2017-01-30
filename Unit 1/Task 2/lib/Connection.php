<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Education\Unit1\Task2;

use Exception;

class Connection {
    /**
     * @var string $ip
     */
    private $ip;

    /**
     * @var integer $port
     */
    private $port;

    /**
     * @var resource $socket
     */
    private $socket;

    /**
     * Connection constructor.
     *
     * @param string|null $ip
     * @param integer|null $port
     */
    public function __construct($ip = null, $port = null) {
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * @return $this
     */
    public function connect() {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_connect($this->socket, $this->ip, $this->port);

        return $this;
    }

    /**
     * @param resource $socket
     */
    public function setSocket($socket) {
        $this->socket = $socket;
    }

    /**
     * @return resource
     *
     * @throws Exception
     */
    public function createSocket() {
        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        if (!$this->socket) {
            throw new Exception(socket_strerror(socket_last_error()));
        }

        if (!socket_bind($this->socket, $this->ip, $this->port)) {
            throw new Exception(socket_strerror(socket_last_error()));
        }

        socket_listen($this->socket);

        return $this->socket;
    }

    /**
     * @param integer $length
     *
     * @return string
     *
     * @throws Exception
     */
    public function read($length = 1024) {
        $data = '';
        $receivedBytes = socket_recv($this->socket, $data, $length, 0);

        if (!$receivedBytes) {
            throw new Exception(socket_strerror(socket_last_error()));
        }

        return $data;
    }

    /**
     * @param mixed $data
     *
     * @return void
     *
     * @throws Exception
     */
    public function write($data) {
        $sentBytes = socket_write($this->socket, $data);

        if (!$sentBytes) {
            throw new Exception(socket_strerror(socket_last_error()));
        }
    }

    /**
     * @return void
     */
    public function close() {
        socket_close($this->socket);
    }
}