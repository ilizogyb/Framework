<?php

namespace Framework\Validation\Filter;

/**
 * ���� ��������� ������� ��� ��������
 * Գ���� ������ �������� �� ���������
 * @autor Lizogyb Igor
 * @since v 1.0
 */
class NotBlank
{
    /**
     * ����� ��������� �������� ��� ���� ��������
     * @param string $param
     * @return boolean �������� �������� ���� ��������
     * ������� ��������
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
     * ����� ��� ��������� ����������� ��� ������� ��� 
     * ������� �� ��� ��������
     * @return string
     */
    public function getMessage() {
        return "Input value must be not a blank!";
    }
}
