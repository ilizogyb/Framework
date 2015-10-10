<?php

namespace Framework\Validation;

/**
 * Клас реалізація Валідатора
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
     * Метод для ініціалізації Валідатора
     * @param Object $value модель для валідації
     */
    public function __construct($value = null)
    {
       $this->model = $value;
       if($value != null) {
           $this->setRules();
       }
    }
    
    /**
	 * Метод для встановлення правил валідації
	 * @param string array  набір правил у вигляді масиву
	 * Приклад використання із фільтрами:
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
	 * Метод для валідації об'єкту
	 * @return boolen булеве значення істиності якщо значення
	 * параметрів об'єкту відповідає заданим в правилах
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
	 * Метод для валідації дати
	 * @param string $value рядок із значенням дати
	 * @param string $format значення формату дати 
	 * @return boolen булеве значення істиності якщо значення
	 * є валідною датою
	 */
    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
    
    /**
	 * Метод для перевірки того що значення не порожнє
	 *
	 * @param	string $value рядок із значенням
	 * @return	bool булеве значення істиності якщо значення
	 * не порожнє
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
	 * Метод для перевірки валідності ел. пошти
	 *
	 * @param	string $value рядок із значенням
	 * @return	bool булеве значення істиності якщо значення
	 * є електронною адресою
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
	 * Метод для валідації цілих чисел
	 * @param string $value рядок із значенням числа
	 * @return boolen булеве значення істиності якщо значення
	 * є ціле число
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
	 * Метод для валідації чисел з плаваючою крапкою
	 * @param string $value рядок із значенням числа
	 * @return boolen булеве значення істиності якщо значення
	 * є число з плаваючою крапкою
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
	 * Валідація URL
	 * @param string $value рядок із значенням URL для валідації
	 * @return boolen булеве значення істиності якщо значення
	 * є коректним URL
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
	 * Метод для додавання помилок в стек помилок валідації
	 * @param string $message рядок із значенням помилки
	 */        
    public function addError($message)
    {
        array_push($this->error_messages, $message);    
    } 
    
	/**
	 * Метод для отримання масиву помилок валідації
	 * @return string array список помилок
	 */
    public function getErrors()
    {
        return $this->error_messages;    
    }
    
    /**
     * Метод рядкового представлення інформації про поточний екземпляр 
     * валідації
     * return string рядок із відомостями про стан об'єкту
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
