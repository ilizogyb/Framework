<?php

namespace Framework\Validation;

/**
 * ���� ��������� ���������
 * @autor Lizogyb Igor
 * @since 1.0
 */
class Validator
{
    protected $isEmpty;
    public $model;
    public $value;
    protected $rules = array();
    public $error_messages = array();
    
    /**
     * ����� ��� ����������� ���������
     * @param Object $value ������ ��� ��������
     */
    public function __construct($value = null)
    {
       $this->model = $value;
       if($value != null) {
           $this->setRules();
       }
    }
    
    /**
	 * ����� ��� ������������ ������ ��������
	 * @param string array  ���� ������ � ������ ������
	 * ������� ������������ �� ���������:
	 * array(
     *       'title'   => array(new NotBlank()),
     *       'content' => array(new NotBlank())
     *  );
	 */
    public function setRules($rules = null)
    {
        if($rules === null) {        
            $this->rules = $this->model->getRules();
        } else {
            $this->rules = $rules;
        }
    }
    
	/**
	 * ����� ��� �������� ��'����
	 * @return boolen ������ �������� �������� ���� ��������
	 * ��������� ��'���� ������� ������� � ��������
	 */
    public function isValid()
    { 
		$field = array_keys($this->rules);

		for($i = 0; $i < count($field); $i++) { 	
			if(isset($this->model->$field[$i]) && isset($this->rules[$field[$i]])) {
				foreach($this->rules[$field[$i]] as $value) {
					if(!$value->getParam($this->model->$field[$i])) {
						$this->addError($value->getMessage());
					}
				} 
			}	
		}

		if(isset($this->model->date)) {
			if(!$this->validateDate($this->model->date)) {
				$this->addError("Error in the post date!");
			}
		}
		if(count($this->getErrors()) === 0) {
			return true;
		} else {
			return false;
		}
    }
    
    /**
	 * ����� ��� �������� ����
	 * @param string $value ����� �� ��������� ����
	 * @param string $format �������� ������� ���� 
	 * @return boolen ������ �������� �������� ���� ��������
	 * � ������� �����
	 */
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    /**
	 * ����� ��� �������� ���� �� �������� �� ������
	 *
	 * @param	string $value ����� �� ���������
	 * @return	bool ������ �������� �������� ���� ��������
	 * �� ������
	 */
    public function required($value)
    {
		if ( ! is_array($value))
		{
			return (trim($value) == '') ? FALSE : TRUE;
		}
		else
		{
			return ( ! empty($value));
		}
	}
	
	/**
	 * ����� ��� �������� �������� ��. �����
	 *
	 * @param	string $value ����� �� ���������
	 * @return	bool ������ �������� �������� ���� ��������
	 * � ����������� �������
	 */
	public function isCorrectEmail($value)
	{
		$pattern = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
		
		if (!is_string($value) || strlen($value) >= 320) {
			return false;
		} else {
			$result = preg_match($pattern, $value);
			return ($result != 0) ? TRUE : FALSE;
		}
	}
	
  	/**
	 * ����� ��� �������� ����� �����
	 * @param string $value ����� �� ��������� �����
	 * @return boolen ������ �������� �������� ���� ��������
	 * � ���� �����
	 */  
    public function isInteger($value) 
    {
		$result = filter_var($value, FILTER_VALIDATE_INT);
		if($result) {
			return is_integer($result);
		} else {
			return false;
		}
	}
	
	/**
	 * ����� ��� �������� ����� � ��������� �������
	 * @param string $value ����� �� ��������� �����
	 * @return boolen ������ �������� �������� ���� ��������
	 * � ����� � ��������� �������
	 */
	public function isFloat($value) 
    {
		$result = filter_var($value, FILTER_VALIDATE_FLOAT);
		if($result) {
			return is_float($result);
		} else {
			return false;
		}
	}
	
	/**
	 * �������� URL
	 * @param string $value ����� �� ��������� URL ��� ��������
	 * @return boolen ������ �������� �������� ���� ��������
	 * � ��������� URL
	 */
	function isCorrectURL($value)
	{
		$pattern = '/^http(s)?:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i';
		if (is_string($value) && strlen($value) < 2000) {
			$result = preg_match($pattern, $value);
			return ($result != 0) ? TRUE : FALSE;
		} else {
			return false;
		}
	}
	
	/**
	 * ����� ��� ��������� ������� � ���� ������� ��������
	 * @param string $message ����� �� ��������� �������
	 */        
    public function addError($message)
    {
        array_push($this->error_messages, $message);    
    } 
    
	/**
	 * ����� ��� ��������� ������ ������� ��������
	 * @return string array ������ �������
	 */
    public function getErrors()
    {
        return $this->error_messages;    
    }
    
    /**
     * ����� ��������� ������������� ���������� ��� �������� ��������� 
     * ��������
     * return string ����� �� ���������� ��� ���� ��'����
     */
    public function __toString()
    {
        $str = '';
        if(count($this->error_messages) === 0) {
            $str = "<br>Everything is ok!<br>";        
        } else {
            foreach($this->error_messages as $key=>$value) {
                $str .= "<br>[$key] ".$value . '<br>';
            }        
        }
        return $str;
    }  

} 
