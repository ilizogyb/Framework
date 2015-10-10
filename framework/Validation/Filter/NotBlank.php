<?php

namespace Framework\Validation\Filter;

/**
 * Клас реалізація фільтра для контенту
 * Фільтр виконує перевірку на порожність
 * @autor Lizogyb Igor
 * @since v 1.0
 */
class NotBlank
{
    /**
     * Метод отримання значення для його перевірки
     * @param string $param
     * @return boolean значення істиності якщо параметр
     * пройшов перевірку
     */
    public function getParam($param)
    {
        if(!empty($param) && strlen($param) > 0) {    
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
        return "Input value must be not a blank!";
    }
}
