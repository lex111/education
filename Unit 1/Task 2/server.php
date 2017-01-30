<?php

use WS\Education\Unit1\Task2\Connection;
use WS\Education\Unit1\Task2\Server;

include __DIR__."/bootstrap.php";

$server = new Server('127.0.0.1', 10100);

$fileStrings = file(__DIR__.'/data/file.txt');
$server->registerHandler(function (Connection $connection) use ($fileStrings) {
    $number = (int) $connection->read();
    if ($number > count($fileStrings) - 1) {
        $number = $number % count($fileStrings);
    }
    $string = $fileStrings[$number - 1];
    echo $number . ":" . $string . "\n";
    $connection->write($string);
});

echo "Server starting... \n";

$server->listen();
