<?php

class DB
{
    private $connection;
    private static $_instance;

    final private function __construct($dsn = 'mysql:dbname=bash;host=localhost', $user = 'root', $password = '')
    {
        $this->connection = new PDO($dsn, $user, $password);
    }

    final public static function run()
    {
        return self::$_instance === null ? self::$_instance = new self : self::$_instance;
    }

    public function  getConn()
    {
        return $this->connection;
    }

    final private function __clone()
    {
    }

    final private function __wakeup()
    {

    }
}

class Models
{
    const ERRORVALID = 'error';
    /**
     * @var string Для хранения ошибок валидации
     */
    public $errorValid;
    /**
     * @var string сообщение для пользователя
     */
    public $message;
    /**
     * @var array Вывод ошибки пользователю
     */
    public $error = array();
    /**
     * @var array Массив принятых данных
     */
    public $attributes = array();

    /**
     * @var $status bool статус выполненного запроса
     */
    public $status;

    protected $query;

    /**
     * Метод для типа запроса SELECT
     * @param string $from строка с таблицей
     * @param string $field строка с полями через запятую, если в параметре ничего не указать то выберутся все поля подобно запросу SELECT *
     * @return $this
     */
    public function select($from, $field = '*')
    {
        $this->query = "SELECT " . $field . " FROM " . $from . " ";
        return $this;
    }

    /**
     * Метод задает условие запроса
     * @param string $condition строка с условием запроса
     * @return $this
     */
    public function where($condition)
    {

        $this->query .= "WHERE " . $this->prepareWhere($condition) . " ";
        return $this;
    }

    protected function prepareWhere($condition)
    {
        if(!is_array($condition)) {
            return $condition;
        } else {
            $tmp = [];
            foreach($condition as $field => $value) {
                $tmp[] = $field . " = " . $value;
            }
            return implode(' AND ', $tmp);
        }
    }

    /**
     * Сортировка запрoса
     * @param $field string строка с указанием поля для сортировки
     * @param $param string cтрока с параметром сортировки ASC/DESC
     * @return $this
     */
    protected function orderBy($field, $param)
    {
        $this->query .= "ORDER BY " . $field . " " . $param . " ";
        return $this;
    }

    /**
     * Дополнительная сортировка запрса
     * @param $field string строка с указанием поля для сортировки
     * @param $param string cтрока с параметром сортировки ASC/DESC
     * @return $this
     */
    public function addOrderBy($field, $param)
    {
        $this->query .= "," . $field . " " . $param . " ";
        return $this;
    }

    /**
     * Метод определяет лимит запроса
     * @param int $limit
     * @param int $offset
     * @return $this
     */
    public function limit($limit, $offset = 0)
    {
        $this->query .= "LIMIT " . $limit . " OFFSET " . $offset . "";
        return $this;
    }

    /**
     * Выполнение запроса
     * @return $this
     */
    protected function execute()
    {
        $this->query = DB::run()->getConn()->prepare($this->query);
        $this->query->execute();
        return $this;
    }

    /**
     * Извлечение результата
     * @return mixed
     */
    public function fetch()
    {
        return $this->execute()->query->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Извлечение множественного результата
     * @return mixed
     */
    public function fetchAll()
    {
        return $this->execute()->query->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Число затронутых строк
     * @return int
     */
    public function count()
    {
        return $this->execute()->query->rowCount();
    }

    /**
     * Запись в базу данных
     * @param $values array массив ключ - значение, ключ - название поля
     * @param $from string имя таблицы
     * @return $this
     */
    public function insert($values,$from){
        $this->query = "INSERT INTO ".$from."";
        $this->query .= " (`" . implode('`,`',array_keys($values)) . "`) ";
        $this->query .= "VALUES ('" . implode("', '", $values) . "') ";
        return $this;
    }

    /**
     * Обработка входных данных
     * @param $param string входные данные
     * @return string
     */
    protected function beforeValid($param){
        $result = trim(htmlspecialchars($param));
        return $result;
    }

    /** Метод принимает входные данные
     * @param array $request
     */
    public function setAttributes($request){
        $this->attributes = $request;
        unset($this->attributes['action']);
    }

    public function getError(){
        return self::ERRORVALID;
    }
}
