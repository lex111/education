<?php

namespace WS\Education\Unit1\Task4\Example;

use WS\Education\Unit1\Task4\ActiveRecord;

class Book extends ActiveRecord {
    public function tableName() {
        return 'books';
    }

    public function getMap() {
        return array(
            'name',
            'description',
            'author',
            'date'
        );
    }
}