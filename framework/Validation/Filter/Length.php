<?php

namespace Framework\Validation\Filter;

/**
 * ���� ��������� ������� ��� ��������
 * Գ���� ������ �������� �� ����� ��������
 * @autor Lizogyb Igor
 * @since v 1.0
 */
class Length
{
    protected $min_length;
    protected $max_length;
    protected $val;
    
    /**
     * ����������� ������� ������������ ��� ��� ��������
     * @param int|string $min �������� �������� ������
     * @param int|string $max ����������� �������� ������
     */
    public function __construct($min, $max)
    {
            $this->min = $min;
            $this->max = $max;
    }
    
    /**
     * ����� ��������� �������� ��� ���� ��������
     * @param string $param
     * @return boolean �������� �������� ���� ��������
     * ������� ��������
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
     * ����� ��� ��������� ����������� ��� ������� ��� 
     * ������� �� ��� ��������
     * @return string
     */
    public function getMessage() {
        return "Error in title length " . $this->val . ' has: ' .strlen($this->val) . 'char(s) but must has length in range (4..100)'  ;
    }
}
