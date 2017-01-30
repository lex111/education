<?php
/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */

namespace WS\Education\Unit1\Task1;

class Stack implements Collection {
    /**
     * @var array $collection
     */
    private $collection;

    /**
     * Stack constructor.
     *
     * @param string $type
     */
    public function __construct($type = 'stack')
    {
        $this->collection = array();
    }

    /**
     * @param $el
     */
    public function push($el)
    {
        array_push($this->collection, $el);
    }

    /**
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->collection);
    }

    /**
     * @return int
     */
    public function size()
    {
        return count($this->collection);
    }
}