<?php

namespace WS\Education\Unit1\Task2;

/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

class Client {
    /**
     * @var Connection $connection
     */
    private $connection;

    /**
     * Client constructor.
     *
     * @param string $ip
     * @param integer $port
     */
    public function __construct($ip, $port) {
        $connection = new Connection($ip, $port);
        $this->connection = $connection->connect();
    }

    /**
     * @param integer $number
     */
    public function send($number) {
        $this->connection->write($number);
    }

    /**
     * @return string
     */
    public function receive() {
        return $this->connection->read();
    }

    /**
     * @return void
     */
    public function close() {
        $this->connection->close();
    }
}