<?php
/**
 * Класс реалізація конфігуратора підключення до бази данних
 * @since Igor Lizogyb
 * @version 1.0
 */

namespace Framework\Model;

class Config
{
    private static $instance = null;
    private $connections = array();
    private $dateFormat = \DateTime::ISO8601;
    private $defaultConnection = 'development';
    
    final private function __construct(){}
    final private function __clone(){}

    /**
     * Статичний метод отриманя екземпляру класу Config 
     * @return Config синглтон об'єкт 
     */
    public static function getInstance()
    {
        if(null === self::$instance) {
            self::$instance = new self();        
        }
        return self::$instance;  
    }

    /**
     * Встановлення списку підключень до бази данних
	 * <code>
	 * $config->setConnections(array(
     *     'development' => 'mysql://username:password@127.0.0.1/database_name'));
     * </code>
	 *
	 * @param array $connections Масив із з'єднаннями
	 * @return void
	 * @throws Exception
	 */
    public function setConnections($connections)
    {
        if(!is_array($connections)) {
            throw new \Exception('Input value must be array');
        }
        $this->connections = $connections;
    }

    /**
     * Метод для отримання параметру підключення за його ключем
     * @param string $name ключ параметру підключення
     * @return string параметр підключення
     */
    public function getConnection($name)
    {
        if(array_key_exists($name, $this->connections)) {
            return $this->connections[$name];
        } else {
            return null;
        }     
    }

    /**
     * Метод для отримання всіх наявних параметрі підключення
     * @return array параметри підключення
     */
    public function getConnections()
    {
        return $this->connections;    
    }

    /**
     * Метод встановлення з'єднання за замовчуванням
     * @param $name назва ключа параметру з'єднання
     */
    public function setDefaultConnection($name)
    {
        $this->defaultConnection = $name;
    }

    /**
     * Метод отримання з'єднання за замовчуванням
     *
     */
    public function getDefaultConnection()
    {
        return $this->defaultConnection;     
    }

    /**
     * Метод отримання параметру з'єднання за замовчуванням
     * @return string параметр з'єднання
     */
    public function getDefaultParamString()
    {
        if (array_key_exists($this->defaultConnection, $this->connections)) {
            return $this->connections[$this->defaultConnection];        
        }
        return null;        
    }

    /**
	 * Метод для встановлення формату дати
	 *
	 * Використовується для date() function.
	 *
	 * @link http://us.php.net/manual/en/function.date.php
	 * @param string $format
	 */

	public function setDateFormat($format)
	{
        $this->dateFormat = $format;
	}

    /**
	 * Метод повертає формат дати
	 *
	 * @return string
	 */
	public function getDateFormat()
	{
		return $this->dateFormat;
	}
}
?>


