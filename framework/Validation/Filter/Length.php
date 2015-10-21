<?php

namespace Framework\Validation\Filter;

/**
 * Клас реалізація фільтра для контенту
 * Фільтр виконує перевірку на розмір контенту
 * @autor Lizogyb Igor
 * @since v 1.0
 */
class Length
{
    protected $min_length;
    protected $max_length;
    protected $val;
    
    /**
     * Ініціалізація фільтра встановлення меж для контенту
     * @param int|string $min мінімальне значення розміру
     * @param int|string $max максимальне значення розміру
     */
    public function __construct($min, $max)
    {
            $this->min = $min;
            $this->max = $max;
    }
    
    /**
     * Метод отримання значення для його перевірки
     * @param string $param
     * @return boolean значення істиності якщо параметр
     * пройшов перевірку
     */
    public function getParam($val)
    {
        $this->val = $val;
        if($this->min <= strlen($val) && strlen($val) <= $this->max) {
            return true;
        } else {
            return false;        
        }
    }
    
    /**
     * Метод для отримання повідомлення про помилку яка 
     * виникла під час перевірки
     * @return string
     */
    public function getMessage() {
        return "Error in title length " . $this->val . ' has: ' .strlen($this->val) . 'char(s) but must has length in range (4..100)'  ;
    }
}
