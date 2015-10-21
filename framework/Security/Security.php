<?php
/**
* Security клас реалізація функціоналу роботи додатку з користувачами
* @autor Lizogyb Igor
* @since 1.0
*/

namespace Framework\Security;
use Framework\DI\Service;

class Security
{
    /**
     * Метод для встановлення поточного користувача
     * @param User $user поточний користувач
     */
    public function setUser($user)
    {
        Service::get('session')->write('user', $user);
    }
    
    /**
     * Метод для перевірки аутентифікації користувача
     * @return boolean значення істини якщо користувач 
     * аутентифікований інакше значення хиби
     */
    public function isAuthenticated()
    {
        return !empty($_SESSION['user']);
    }
    
    /**
     * Метод для очистки даних поточного користувача
     * та знищення його об'єкта з сесії
     */
    public function clear()
    {
        Service::get('session')->del('user');
    }
    
}
