<?php

class DB
{
    private $dsn = 'mysql:dbname=bash;host=localhost';
    private $user = 'root';
    private $password = '';
    private $conection;
    protected static $instance;

    private function __construct()
    {
        try
        {
            $this->conection = new PDO($this->dsn,$this->user,$this->password);
        }
        catch (PDOException $e)
        {
            echo 'Подключение не удалось: ' . $e->getMessage();
        }

    }

    public static function run()
    {
        if (null === self::$instance)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __clone(){}

}

class Models
{
    protected $query;

    /*метод для типа запроса SELECT,
    $field - строка с полями через запятую, если в параметре ничего не указать
    то выберутся все поля подобно запросу SELECT *
    $from - строка с таблицей */
    public function select($from,$field = NULL)
    {
        if(is_string($field))
            $this->query = "SELECT ".$field." FROM ".$from." ";
        elseif($field === NULL)
            $this->query = "SELECT * FROM ".$from." ";
        return $this;
    }

    /*Метод задает условие запроса
    $condition строка с условием запроса*/
    public function where($condition)
    {
        $this->query .= "WHERE ".$condition." ";
        return $this;
    }

    /*Сортировка запрса
    $field строка с указанием поля для сортировки
    $param Строка с параметром сортировки ASC/DESC */
    protected function orderBy($field,$param)
    {
        $this->query .= "ORDER BY ".$field." ".$param." ";
        return $this;
    }

    /*Дополнительная сортировка запрса
    $field строка с указанием поля для сортировки
    $param Строка с параметром сортировки ASC/DESC */
    public function addOrderBy($field,$param)
    {
        $this->query .= ",".$field." ".$param." ";
        return $this;
    }

    /*Метод определяет лимит запроса*/
    public function limit($offset,$count = NULL)
    {
        if($count === NULL)
            $this->query .= "LIMIT ".$offset." ";
        else
            $this->query .= "LIMIT ".$offset.",".$count."";
        return $this;
    }
    //Выполнение запроса
    public function execute()
    {
        $this->query = DB::run()->prepare($this->query);
        $this->query->execute();
        return $this;
    }

    //Извлечение результата
    public function fetch()
    {
        $result = $this->query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    //Извлечение множественного результата
    public function fetchAll()
    {
        $result = $this->query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    //Число затронутых строк
    public function count()
    {
        $count = $this->query->columnCount();
        return $count;
    }

}
