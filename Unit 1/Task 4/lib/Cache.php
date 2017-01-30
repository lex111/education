<?php

namespace WS\Education\Unit1\Task4;

class Cache {
    /**
     * @var Cache $instance
     */
    private static $instance;

    /**
     * @var array $cache
     */
    private $cache = array();

    /**
     * Cache constructor.
     */
    private function __construct() {}

    public static function getInstance() {
        if (!static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * @param string $name
     *
     * @return bool|mixed
     */
    public function get($name) {
        $hash = md5($name);

        if (!empty($this->cache[$hash])) {
            return $this->cache[$hash];
        }

        return false;
    }


    /**
     * @param string $name
     * @param array $value
     *
     * @return void
     */
    public function set($name, $value) {
        $hash = md5($name);

        $this->cache[$hash] = $value;
    }
}