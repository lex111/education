<?php

namespace WS\Education\Unit1\Task4;

use RuntimeException;

abstract class ActiveRecord {
    /**
     * @var Database $database
     */
    protected static $database;

    /**
     * @var array $data
     */
    protected $data;

    /**
     * @var Cache $cache
     */
    protected $cache;

    /**
     * ActiveRecord constructor.
     *
     * @param array $data
     */
    public function __construct($data = array()) {
        $this->data = $data;
        $this->cache = Cache::getInstance();
    }

    /**
     * @param Database $database
     */
    static public function setDatabase($database) {
        if (empty(self::$database)) {
            self::$database = $database;
        }
    }

    /**
     * @return string
     */
    abstract public function tableName();

    /**
     * @return array
     */
    abstract function getMap();

    /**
     * @return string
     */
    public function primaryKey() {
        return 'id';
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function __set($name, $value) {
        $methodName = 'get' . ucfirst($name);

        if (method_exists($this, $methodName)) {
            $this->$methodName($value);
        }

        $this->data[$name] = $value;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name) {
        $methodName = 'get' . ucfirst($name);

        if (method_exists($this, $methodName)) {
            $value = $this->$methodName();

            return $value;
        }

        return $this->data[$name];
    }

    /**
     * @param mixed $conditions
     */
    public function find($conditions) {
        $conditionsData = array();

        if (is_numeric($conditions)) {
            $conditionsData[$this->primaryKey()] = $conditions;
        } else {
            foreach ($conditions as $fieldName => $fieldValue) {
                $conditionsData[$fieldName] = $fieldValue;
            }
        }

        $conditionString = $this->getPlaceholderString($conditionsData, ' && ');

        $query = sprintf(
            'SELECT * FROM %s WHERE %s',
            $this->tableName(),
            $conditionString
        );

        if (!$result = $this->cache->get($query)) {
            $result = self::$database->query($query, $conditionsData)->fetch();
            $this->cache->set($query, $result);
         }

        if (empty($result)) {
            throw new RuntimeException('Record not found');
        } else {
            $this->data = $result;
        }
    }

    /**
     * @return ActiveRecord[]
     */
    public function findAll() {
        $query = sprintf(
            'SELECT * FROM %s',
            $this->tableName()
        );

        $result = array();

        $items = self::$database->query($query)->fetchAll();

        foreach ($items as $item) {
            $result[] = new static($item);
        }

        return $result;
    }

    /**
     * @return string|bool
     */
    public function save() {
        if (!empty($this->data['id'])) {
            return $this->update();
        } else {
            return $this->add();
        }
    }

    /**
     * @return string|bool
     */
    private function add() {
        $fields = implode(',', array_keys($this->data));
        $values = ':' . implode(', :', array_keys($this->data));

        $query = sprintf(
            'INSERT INTO %s (%s) VALUES(%s)',
            $this->tableName(),
            $fields,
            $values
        );

        self::$database->query($query, $this->data);

        return self::$database->getLastInsertId() ?: false;
    }

    /**
     * @return bool
     */
    private function update() {
        $updateFields = array();

        foreach ($this->data as $fieldKey => $fieldValue) {
            if ($fieldKey !== $this->primaryKey() && $fieldValue) {
                $updateFields[$fieldKey] = $fieldValue;
            }
        }

        $conditionsString = $this->getPlaceholderString($updateFields);

        $query = sprintf(
            'UPDATE %s SET %s WHERE %s = %s',
            $this->tableName(),
            $conditionsString,
            $this->primaryKey(),
            $this->data[$this->primaryKey()]
        );

        return (bool) self::$database->query($query, $updateFields)->rowCount();
    }

    /**
     * @return bool
     */
    public function delete() {
        if (!$this->data['id']) {
            throw new RuntimeException('Unable to remove non-existent record');
        }

        $query = sprintf(
            'DELETE FROM %s WHERE %s = %s',
            $this->tableName(),
            $this->primaryKey(),
            $this->data['id']
        );

        return (bool) self::$database->query($query)->rowCount();
    }


    /**
     * @param array $fields
     * @param string $separator
     *
     * @return string
     */
    private function getPlaceholderString(array $fields, $separator = ', ') {
        return implode($separator, array_map(
            function ($fieldValue, $fieldName) {
                return $fieldName . ' = :' . $fieldName;
            },
            $fields,
            array_keys($fields)
        ));
    }
}