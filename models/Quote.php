<?php
require_once('/lib/Models.php');

class Quote extends Models
{
    /**
     * @var int Колличество элементов выводимых на страницу
     */
    public $items = 10;

    /** Валидация модели
     * @return bool
    */
    public function validate(){

        foreach($this->attributes as $key => $value){

            if($value === ""){
                $this->errorValid = $this->getError();
                $this->error[$key] = "Поле обязательно для заполнения";
            }
            else{
                $key = $this->beforeValid($value);
            }
        }

        if(isset($this->errorValid)){
            return false;
        }
        if(strlen($this->attributes['title']) <= 100){
            $this->error['title'] = NULL;
        }
        else{
            $this->error['title'] = 'Длинна заголовка не должна превышать 100 символов';
            $this->errorValid = $this->getError();
        }
        if(strlen($this->attributes['author'])<=50){
            $this->error['author'] = NULL;
        }
        else{
            $this->error['author'] = "Имя автора не должно превышать 50 сиволов";
            $this->errorValid = $this->getError();
        }
        if((int)$this->attributes['category'] !== 0){
            $this->error['category'] = NULL;
        }
        else{
            $this->error['category'] = "Не верная категория";
            $this->errorValid = $this->getError();
        }

        if(isset($this->errorValid))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function insertQuote(){
        if($this->validate()){
            $this->insert($this->attributes,'quotes')->execute();
            return $this->status;
        }
        else
        {
            return $this->error;
        }
    }

    /**
     * @param int $page какая страница была запрошена
     * @return array
     */
    public function selectQuoteAll($page = 1)
    {
        $allQuotes = $this->select('quotes')->count();
        $query = $this->select('quotes')->orderBy('datePub', 'ASC');
        return $this->pager($page, $allQuotes, $query);
    }

    /**
     * @param int $page какая страница была запрошена
     * @param $all общее колличество элементов
     * @param $query запрос с подготовленными параметрами
     * @return array массив из результата запроса, общего колличества страниц и активной страницы
     */
    public function pager($page = 1, $all, $query)
    {
        $page = (int)$page;
        $startIndex = ($page - 1) * $this->items;
        $result = $query->limit($this->items,$startIndex)->fetchAll();
        $pages = ceil($all / $this->items);
        $activePage = $page;
        return array('result' => $result, 'pages' => $pages, 'activePage' => $activePage);
    }

    /**
     * @param int $category категория
     * @param int $page какая страница была запрошена
     * @return array массив из результата запроса, общего колличества страниц и активной страницы
     */
    public function selectQuoteCat($category, $page = 1)
    {
        $allQuotes = $this->select('quotes')->where(['category' => $category])->count();
        $query = $this->select('quotes')->where(['category' => $category])->orderBy('datePub', 'ASC');
        return $this->pager($page, $allQuotes, $query);
    }
} 