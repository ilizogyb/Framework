<?php
/**
 * ���� ��������� HTTP Redirect Response
 * ��� ������ � �����������������
 * @autor Lizogyb Igor
 * @since 1.0
 *  
 */

namespace Framework\Response;

class ResponseRedirect extends Response
{
    protected $route;
    
    /**
     * ����������� ��� ����������� Redirect Response
     * @param string $route ���� ��� ���������������
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
     * ����� ��� �������� ��������� �� � HTTP �봹�� 
     * �� ���������������
     */
    public function send() {
        $this->sendHeaders();
    }
}
?>