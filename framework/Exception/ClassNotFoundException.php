<?php
namespace Framework\Exception;

/**
* Клас для обробки виключень типу клас не знайдено
* @autor Lizogyb Igor
* @since 1.0
*/

class ClassNotFoundException extends \Exception 
{
	protected $message;
	protected $code;
	private $previous;
	//Перевизначення виключення
	public function __construct($message = null, $code = 0, Exception $previous = null)
	{
		parent::__construct($message, $code, $previous);
		echo "Class:  {$this->message}<br/>";
	}
	
	/**
	* Рядкове відображення об'єкту
	* @return рядок із змістом помилки
	*/
	public function __toString() {
       return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
?>