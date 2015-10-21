<?php
/**
 * Клас реалізація HTTP Json Response 
 * для роботи з JSON
 * @autor Lizogyb Igor
 * @since 1.0
 *  
 */

namespace Framework\Response;

class JsonResponse extends Response
{
    /**
     * Конструктор для ініціалізації JSON Response
     * @param string array дані для JSON
     */
    public function __construct($input)
    {
        $this->options['sendHeaders'] = true;
        $this->setStatusCode(200);
        $this->setHeader('Content-Type', 'application/json', true);
        $this->setContent($input);
        $this->send();
    }
   
    /**
     * Метод для відправки заголовків та контенту в HTTP клієнт 
     *
     */
    public function send() {
        $this->sendHeaders();
        echo json_encode($this->getContent());
    }
}
?>
