<?php
/**
 * Клас реалізація підключення до бази данних
 * 
 * @autor Lizogyb Igor
 * @since 1.0
 */
 
namespace Framework\Model;

use PDO;

class Connection
{
    /**
	 * Опції PDO за замовчуванням для кожного з'єднання
     * @var array
	 */
	static $PDO_OPTIONS = array(
		PDO::ATTR_CASE				=> PDO::CASE_LOWER,
		PDO::ATTR_ERRMODE			=> PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_ORACLE_NULLS		=> PDO::NULL_NATURAL,
		PDO::ATTR_STRINGIFY_FETCHES	=> false);

	public $connection;
    protected $dsn;
    protected $user = 'root';
    protected $pass = '52651';
    
    /**
     * Ініціалізація данних для підключення до бази данних
     * @param string $dsn DSN рядок з параметрами для створення
     * підключення
     */
    public function __construct($dsn = null)
    {
        $config = Config::getInstance();
    
       if ($dsn === null) {
           $this->dsn = $config->getDefaultParamString();
       } else {
           $this->dsn = $dsn;
       }
    }

    /**
     * Метод для створення підключення до бази данних
     * @return PDO Object $dbh об'єкт який являється 
     * підключенням до бази данних
     */
    public function createConnection()
    {
        try {
            $dbh = new PDO($this->dsn, $this->user, $this->pass, self::$PDO_OPTIONS);
        } catch(PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());       
        }
        return $dbh;
    }
}
?>
