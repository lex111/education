<?php

use PHPUnit\Framework\TestCase;
use WS\Education\Unit1\Task4\Database;
use WS\Education\Unit1\Task4\ActiveRecord;
use WS\Education\Unit1\Task4\Example\Book;

class ActiveRecordTest extends TestCase {
    const CONFIG_PATH = __DIR__ . '/config.test.ini';
    const DB_FILE_PATH = __DIR__ . '/db.db3';

    public static function setUpBeforeClass() {
        $database = new Database(static::CONFIG_PATH);

        ActiveRecord::setDatabase($database);

        chmod(static::DB_FILE_PATH, '0777');

        $createTableQuery = '
            CREATE TABLE IF NOT EXISTS books (
               id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
               name VARCHAR(250) NULL,
               author VARCHAR(250) NULL,
               description VARCHAR(250) NULL
            );
        ';

        $database->query($createTableQuery);
    }

    public static function tearDownAfterClass() {
        unlink(static::DB_FILE_PATH);
    }

    public function testAddRecord() {
        $book = new Book([
            'name' => 'Собачье сердце',
            'author' => 'Михаил Булгаков',
            'description' => 'В центре сюжета - опыт ученого профессора Преображенского по созданию собаки из человека...'
        ]);
        $recordId = $book->save();

        $this->assertEquals(1, $recordId);
    }

    public function testFindRecord() {
        $book = new Book();
        $book->find(['name' => 'Собачье сердце']);

        $this->assertEquals('Собачье сердце', $book->name);
    }

    public function testFindAllRecord() {
        $book = new Book();
        $items = $book->findAll();

        $this->assertInstanceOf(Book::class, $items[0]);
    }

    public function testUpdateRecord() {
        $book = new Book();
        $book->find(1);
        $book->author = 'Михаил Афанасьевич Булгаков';
        $result = $book->save();

        $this->assertTrue($result);
    }

    public function testDeleteRecord() {
        $book = new Book();
        $book->find(1);
        $result = $book->delete();

        $this->assertTrue($result);
    }
}