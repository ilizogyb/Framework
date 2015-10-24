<?php

/**
* ActiveRecord клас реалізація функціоналу роботи додатку з базою данних
* Використовуючи PDO дозволяє зберігати об'єкти в БД та відновлювати їх
* з неї.
* @autor Lizogyb Igor
* @since 1.0
*/
namespace Framework\Model;

use Framework\Application;
use Blog\Model\Post;
use Framework\Model\Connection;
use PDO;

abstract class ActiveRecord 
{
    protected static $connection;
    protected $pk;
    public $id;
    protected $bindStr = '';
    
    /**
     * Конструктор в ньому ініціюється ключове поле таблиці БД
     * та з'єднання з базою данних
     * @param Connection  $connection Об'єкт підключення до бази данних
     * @param string $pk головний ключ таблиці
     *
     */
    public function __construct(Connection $connection=null, $pk='id')
    { 
       if (null === $connection) {
            self::$connection = new Connection();
            self::$connection = self::$connection->createConnection();
       } else {
           self::$connection = $connection;
       }
       $this->pk = $pk;
    }
    
    /**
     * Магічний метод для отримання значення поля об'єкту
     * @param string $param назва властивості
     *
     */   
    public function __get($param)
    {
        if(isset($this->$param)) {
            return $this->$param;        
        } else die(ucfirst($param) . ' is unavailable property');
    }
    
    /**
     * Магічний метод для встановлення значення поля об'єкту
     * @param string $param назва властивості
     * @param string $value значення властивості
     */
    public function __set($param, $value) 
    { 
        if(isset($this->$param)) {
            $this->$param = $value;
        } else die('Parameter '. $value .' is not exists');
    }
    
    /**
     * Метод для створення пыдключення до бази даних
     * @return PDO object
     */
    public static function createConnection()
    {
         if (null === self::$connection) {
            self::$connection = new Connection();
            self::$connection = self::$connection->createConnection();
            $pdo = self::$connection;
        } else {
            $pdo = self::$connection;    
        }
        return $pdo;
    }
    
    
    /**
     * Метод для видалення посту за його ідентифікатором
     * @param string $id ідентифікатор посту, який потрібно
     * видалити
     */
    public static function delete($id)
    { 
        $pdo = self::createConnection();
        $stmt = $pdo->prepare('DELETE FROM ' . static::getTable() . ' WHERE id = ?'); 
        $stmt->execute(array($id));
        return true;
    }
    
    /**
     * Метод пошуку посту за його ідентифікатором, або виводу
     * всіх наявних постів
     * @param string $id ідентифікатор посту, якщо 
     * $id == 'all' виводяться всі наявні пости
     * @return Post пост або масив постів
     */ 
    public static function find($id)
    {
        $pdo = self::createConnection();    
        
        $result = null;

        if (preg_match("/^\d*$/",$id)) {
            $stmt = $pdo->prepare('SELECT * FROM ' . static::getTable() . ' WHERE id = ?');
            $stmt->execute(array($id)); 
            $result = $stmt->fetch(PDO::FETCH_OBJ);
        } elseif($id === 'all') {
            $sql = 'SELECT * FROM ' . static::getTable();
            $stmt = $pdo->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ); 
        }
        return $result;
    }
    
    /**
     * Метод для побудови частини SQL запиту 
     * виконує приєднання данних із змінних у поля таблиці
     * @param string array $fields масив із полями таблиці
     * @param string array $values масив із данними для цих полів
     *
     * Приклад використання:
     * bind(array("title","content"), array("Test title","Test content"));
     *
     * Метод повернеться рядок:
     * `title`='Test title', `content`='Test content'
     *  який можна використовувати для побудови SQL запиту
     *
     * @return string рядок із частиною SQL запиту
     */
    public function bind($fields, $values)
    {
        $str = '';
        $i = 0;
        foreach($fields as $field) { 
                $str .= ' `'.$field.'`='.'\''.$values[$i++].'\''. ', ';
        }
        $str = substr($str, 0, -2);
        $this->bindStr = $str;
    }
    
    /**
     * Метод для отримання масиву ключів записів, які є в БД
     * використовується для збереження даних, 
     * а саме в методі save()
     * для порівняння поточного ключа запису із
     * наявними в БД
     */
    public function getIDs() {
        $pdo = self::$connection;       
        $sql = 'SELECT id FROM `' . $this->getTable() . '`';
        $stmt = $pdo->query($sql);
        $usersIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $usersIds;    
    }

    /**
     * Метод для запису даних до бази данних
     * Якщо об'єкт існує в базі данних, виконується
     * оновлення даних, якщо ні - створюється новий запис
     */
    public function save()
    {  
       //$objData = get_object_vars($this);
       //$keys = array_keys($objData);
       //Тимчасове рішення
        if('Blog\Model\Post'=== get_class($this)) {
            $this->bind(array("title", "content", "date"), array($this->title, $this->content, $this->date));
        } elseif('Blog\Model\User'=== get_class($this)){
            $this->bind(array("email", "password", "role"), array($this->email, md5($this->password), $this->role));    
        }
        
        $pdo = self::$connection;

        if(in_array($this->id, $this->getIDs())) {
            $sql ='UPDATE `' . $this->getTable() . '` SET' . $this->bindStr .'WHERE id = ' . $this->id;
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        } else {
            $sql ='INSERT INTO `' . $this->getTable() . '` SET' . $this->bindStr;
            $stmt = $pdo->prepare($sql);
            echo $sql;
            $stmt->execute();
            $this->id = $pdo->lastInsertId();
        }
        
    }
    
    /**
     * Метод для пошуку користувача за його електронною адресою
     * @param string $email електронна адреса користувача
     * @return Object User об'єкт знайдений користувач
     */
    public static function findByEmail($email)
    {
        $pdo = self::createConnection();    

        $emailPatt = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
        
        if (preg_match($emailPatt, $email)) {
            $stmt = $pdo->prepare('SELECT * FROM ' . static::getTable() . ' WHERE email = ?');
            $stmt->execute(array($email)); 
            $result = $stmt->fetch(PDO::FETCH_OBJ);
        }
        return $result;
    }
}
?>
