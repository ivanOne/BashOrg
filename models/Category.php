<?php
require_once('/lib/Models.php');

class Category extends Models
{
    /**
     * @return array Извлекает категории
     */
    public function selectCategory(){
        $allCategories = $this->select('category')->fetchAll();
        return $allCategories;
    }
} 