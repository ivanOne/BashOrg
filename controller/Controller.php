<?php

require_once('/models/Category.php');
require_once('/models/Quote.php');

class Controller
{
    /**
     * @param int $page Номер страницы
     * @return $quotes Результат вборки цитат
    */
    public static function index($page=1){
        $quotes = new Quote();
        $result = $quotes->selectQuoteAll($page);
        return $result;
    }

    /**
     * @param int $id  Номер категории
     * @param int $page Номер страницы
     * @return $quotes Результат выборки по категориям
    */
    public static function category($id,$page=1){
        $quotes = new Quote();
        $result = $quotes->selectQuoteCat($id,$page);
        return $result;
    }

    /**
     * @param array $val Данные из формы
     */
    public static function insert($val){
        $quotes = new Quote();
        $quotes->setAttributes($val);
        $quotes->insertQuote();
        return $quotes;
    }

    /**
     * @return array $category возвращает категории
     */
    public static function getCategory(){
        $category = new Category();
        $result = $category->selectCategory();
        return $result;
    }

} 