<?php
/**
 * Клас реалізація HTTP Redirect Response
 * для роботи з перенаправленнями
 * @autor Lizogyb Igor
 * @since 1.0
 *  
 */

namespace Framework\Response;

class ResponseRedirect extends Response
{
    protected $route;
    
    /**
     * Конструктор для ініціалізації Redirect Response
     * @param string $route роут для перенаправлення
     */
    public function __construct($route)
    {
        $this->options['sendHeaders'] = true;
        $this->route = $route;
        $this->setStatusCode(303);
        $this->setHeader('Location', $this->route, true);
        $this->send();
    }
    
    /**
     * Метод для відправки заголовків та в HTTP клієнт 
     * та перенаправлення
     */
    public function send() {
        $this->sendHeaders();
    }
}
?>