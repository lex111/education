## Реализация сетевого соединения.

Реализовать программы взаимодействующие через сокет. Клиент отправляет номер строки.
Сервер получает номер строки читает строку из файла, отдает клиенту. Полученную строку сервер выводит в свой стандартный поток вывода. Если строка превышает количество строк файла, сервер
отдает строку согласно остатку от деления общего количества строк файла.

Классы:

* Connection
  * read(int length)
  * write(string)
  * close()
* Server
  * registerHandler (callback)
  * close()
* Client
  * send(string)
  * receive() : string
  * close()
  
Используемые функции PHP при работе с сокетами:
* socket_create
* socket_bind
* socket_listen
* socket_accept
* socket_recv
* socket_write
* socket_connect
* socket_close

**Проверка**

1. Запуск сервера server.php
2. Запуск тестов (phpunit) отдельным процессом