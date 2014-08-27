<?php
require_once('/lib/Models.php');

class Quote extends Models
{
    /**
     * @var int Колличество элементов выводимых на страницу
     */
    public $items = 10;

    public function  validate()
    {
    }

    public function insertQuote()
    {
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
        $result = $query->limit($startIndex, $this->items)->fetchAll();
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