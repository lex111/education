<?php

/**
 * @author Maxim Sokolovsky <sokolovsky@worksolutions.ru>
 */
class ConcurrentTest extends PHPUnit_Framework_TestCase {

    /**
     * @return string
     */
    private function readCount() {
        return file_get_contents(__DIR__.'/../data/counter.txt');
    }

    private function writeCount($content) {
        file_put_contents(__DIR__.'/../data/counter.txt', $content);
    }

    public function testRun() {
        // обнулить файл
        $this->writeCount("0");
        $this->assertEquals("0", $this->readCount());
        // обнулить идентфикаторы запросов
        file_put_contents(__DIR__."/../data/uniq", "");
        // подать 100 запросов одновременно
        for($i = 0; $i < 100; $i++) {
            $fp = fsockopen("task3.unit1.ws-education.dev", 80, $errno, $errstr, 1);
            $http = "GET / HTTP/1.1\r\n";
            $http .= "Host: task3.unit1.ws-education.dev\r\n";
            $http .= "Connection: Close\r\n\r\n";
            fwrite($fp, $http);
            fread($fp, 1);
            fclose($fp);
        }
        sleep(3);
        // убедится что число 100, все запросы зарегистрированы
        $this->assertEquals("100", $this->readCount());
    }
}
