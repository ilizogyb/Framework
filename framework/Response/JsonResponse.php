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
    protected $JSONinput = array();
    /**
     * Конструктор для ініціалізації JSON Response
     * @param string array дані для JSON
     */
    public function __construct($input)
    {
        $this->setStatusCode(200);
        $this->setHeader('Content-Type', 'application/json', true);
        $this->options['sendHeaders'] = false;
        //$this->sendHeaders();
        //$this->setContent($input);
        $this->JSONinput = $input;
        $this->send();
    }
   
    /**
     * Метод для відправки заголовків та контенту в HTTP клієнт 
     *
     */
    public function send() {
        header(implode($this->getHeaders(), '\n'));
        //echo json_encode($this->getContent());
        echo json_encode($this->JSONinput);
    }
}
?>
