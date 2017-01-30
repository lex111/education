<?php

namespace WS\Education\Unit1\Task4;

use PDO;
use PDOStatement;
use PDOException;

class Database {
    /**
     * @var PDO $connection
     */
    public $connection;

    /**
     * @var PDOStatement $statement
     */
    private $statement;

    public function __construct($configFile = 'config.ini') {
        $config = parse_ini_file($configFile, true);
        $dbConfig = $config['db_config'];

        if ($dbConfig['driver'] === 'sqlite') {
            $dsn = sprintf(
                '%s:%s',
                $dbConfig['driver'],
                $dbConfig['path_to_file']
            );
        } else {
            $dsn = sprintf(
                '%s:host=%s;dbname=%s',
                $dbConfig['driver'],
                $dbConfig['hostname'],
                $dbConfig['database']
            );
        }

        try {
            $this->connection = new PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                )
            );
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param $query
     * @param array $data
     *
     * @return PDOStatement|string
     */
    public function query($query, $data = array()) {
        $this->prepare($query);

        foreach ($data as $key => $value) {
            $this->bind(":$key", $value);
        }

        try {
            $this->execute();

            return $this->statement;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    /**
     * @param string $query
     */
    public function prepare($query) {
        $this->statement = $this->connection->prepare($query);
    }

    /**
     * @param string $param
     * @param mixed $value
     *
     * @return void
     */
    public function bind($param, $value) {
        $type = (is_int($value)) ? PDO::PARAM_INT : PDO::PARAM_STR;

        $this->statement->bindValue($param, $value, $type);
    }

    /**
     * @return bool
     */
    public function execute() {
        return $this->statement->execute();
    }

    /**
     * @return string
     */
    public function getLastInsertId() {
        return $this->connection->lastInsertId();
    }
}