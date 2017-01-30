<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Education\Unit1\Task1;

class Queue implements Collection {
    /**
     * @var array $collection
     */
    private $collection;

    /**
     * Queue constructor.
     *
     * @param string $type
     */
    public function __construct($type = 'queue') {
        $this->collection = array();
    }

    /**
     * @param $item
     */
    public function push($item) {
        array_push($this->collection, $item);
    }

    /**
     * @return mixed
     */
    public function pop() {
        return array_shift($this->collection);
    }

    /**
     * @return int
     */
    public function size() {
        return count($this->collection);
    }
}