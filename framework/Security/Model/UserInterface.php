<?php
/**
* UserInterface клас інтерфейс для об'єкта користувач
* Зібрані основні методи які необхідні для об'єкта користувач
* @autor Lizogyb Igor
* @since 1.0
*/

namespace Framework\Security\Model;

interface UserInterface
{
    /**
     * Метод для отримання інформації про назву
     * таблиці в якій збережено дані користувачів
     * @return string назва таблиці
     */
    static function getTable();
    
    /**
     * Метод для отримання інформації про роль(статус) користувача
     * @return string роль(статус) користувача
     */
    public function getRole();
}
?>