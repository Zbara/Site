<?php
/**
 * Class mysql - работа с БД
 */

/** константы */
define("SQL_RESULT_ITEM", 1);
define("SQL_RESULT_ITEMS", 2);
define("SQL_RESULT_COUNT", 3);
define("SQL_RESULT_AFFECTED", 4);
define("SQL_RESULT_INSERTED", 5);

class mysql
{ 
    public $mysqli = false;
 
    /**
     * функция для подключения к БД.
     * @param $db_server сервер бд
     * @param $db_user пользователь базы данных
     * @param $db_pass пароль от базы данных
     * @param $db_name имя бд
     */
    public function __construct($db_server, $db_user, $db_pass, $db_name)
    {
        $this->mysqli = new mysqli($db_server, $db_user, $db_pass, $db_name);
        if ($this->mysqli->connect_error) {
            return $this->errorDatabase($this->mysqli->connect_error, 'CONNECT');
        }
        $this->mysqli->set_charset("utf8mb4");
    }

    /**
     * вывод ошибок
     * @param $error
     * @param string $query
     */
    private function errorDatabase($error, $query)
    {
        die(json_encode(['msg' => $query, 'title' => 'Mysql Error: ' . $error]));
    }

    /**
     * Функция для запросов к БД
     * @param  String $query Запрос SQL
     * @param  int $resultType В каком типе возвращать результат
     * @return Mixed              Результат, в зависимости от $resultType
     */
    public function SQLquery($query, $resultType = SQL_RESULT_ITEM)
    {
        /** @var  $result */
        $result = $this->mysqli->query($query);
        /** ошибка */
        if (!$result) {
            return $this->errorDatabase($this->mysqli->error, $query);
        }
        /** если все нормально отдаем резузьтат */
        switch ($resultType) {
            case SQL_RESULT_ITEM:
                return $result->fetch_assoc();

            case SQL_RESULT_ITEMS:
                $data = [];
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                return $data;

            case SQL_RESULT_COUNT:
                return (int)$result->fetch_assoc()["COUNT(*)"];

            case SQL_RESULT_INSERTED:
                return (int)$this->mysqli->insert_id;

            case SQL_RESULT_AFFECTED:
                return (int)$this->mysqli->affected_rows;
        }
        return [];
    }

    /**
     * очистка строки
     * @param $string
     * @return string
     */
    public function escape($string)
    {
        return $this->mysqli->escape_string($string);
    }

    /**
     * Закрытие коннекшена с БД
     */
    public function closeDatabase()
    {
        $this->mysqli->close();
    }
}