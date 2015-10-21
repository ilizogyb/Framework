<?php
namespace Framework\Session;

/*
 * Клас реалізація Session
 *
 * @author  Lizogyb Igor    
 * @version 1.0   
 */
class Session
{
    private  $lifetime = 1200000; // 14 днів
    private  $cookieName = "cid";
    private  $started = false; // Перемикач початку сесії
    public   $returnUrl;
    protected static $_instance;
    
    /**
     * Закриваємо доступ до функції поза класом.
     *
     */
    private function __construct($id = NULL)
    {
        if($id != null) {
            session_id($id);
        }

    }
    /**
     * Закриваємо доступ до функції поза класом.
     *
     */
    private function __clone(){}
    
    /**
     * Статична функція, яка повертає
     * екземпляр класу або створює новий за
     * необхідності
     *
     * @return Session
     */
    public static function getInstance()
	{
		if(null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
    /**
     * Метод для створення сесії
     *
     */
    public function start() {
        if(!$this->started) {
              session_set_cookie_params ($this->lifetime + time(), '/'); 
              session_name($this->cookieName);
              session_start();
              $this->started = true;
            } 
   }
    
    /**
    * Метод для перевірки створення сессії
    * @return boolean булеве значення істиності якщо сесія створена або хибності коли ні
    *
    */
    public function isCreated()
    {
        return (!empty($_COOKIE[$this->cookieName])) ? true : false;
    } 
    
    /**
     * Метод для отриманння ідентифікатора даної сесії
     * @return string рядок з ідентифікатором сесії
     *
     */
    public function getSessionId()
    {
        return session_id();
    }
   
    /**
     * Метод для встановлення значення в даній сесії
     * @param string $key ключ значення
     * @param string $data  значення
     * @return boolean булеву значення істиності або хибності 
     * залежно від успішності операції запису
     */
    public function write($key, $data)
    {
        if($this->isCreated()) {
            $_SESSION[$key] = $data;
            return true;
        } else {
            return false;        
        }
    }
    
    /**
     * Метод для отриманння значення встановленого в даній сесії
     * @param string $key ключ значення
     * @return string значення ключа
     */
    public function read($key)
    {
        $value = null;
        if(isset($_SESSION[$key]))
        {
            $value = $_SESSION[$key];
        }
        return $value;
    }
    
    /**
     * Метод для видалення значення встановленого в даній сесії
     * @param string $key ключ значення
     */  
    public function del($key)
    { 
        if($this->isCreated()) {
            $_SESSION[$key] = NULL;
            unset($_SESSION[$key]);
        } 
    } 

    /**
     * Метод для очищення даних в поточній сесії
     */    
    public function clear()
    {
        if($this->started) {
            unset($_SESSION);
        } else {
            trigger_error('Session not started', E_USER_WARNING);
        }
    }
 
    /**
     * Метод для знищення поточної сесії
     */ 
    public function destroy()
    {
        if($this->started) {
           $this->started = false;
            unset($_COOKIE[$this->cookieName]);
            setcookie($this->cookieName, '', 1, '/'); 
            session_destroy();            
        } else {
            trigger_error('Session not started', E_USER_WARNING);;
        }
    } 

    /**
     * Метод для перезапуску сесії
     */    
    public function restart()
    {
        if($this->started) {
            $this->destroy();
            $this->start();
        } else {
            trigger_error('Session not started', E_USER_WARNING);
        }
    } 
    
    /**
     * Метод для закриття сесії, наприклад коли дані сесії потрібні
     * лише для читання. Для прикладу, послідовність використання 
     * write=>shutdown start=>read=>shutdown
     *
     */      
    public function shutdown()
    {
        if($this->started) {
            session_write_close();
            $this->started = false;
        } else {
            trigger_error('Session not started', E_USER_WARNING);
        }
    }
    
    /**
     * Метод для встановлення базового роута для
     * повернення на головну сторінку
     */      
    public function setReturnUrl($url){
        if (!strpos($url, 'login')) {
            $this->write('returnUrl', $url);
        }
        $this->returnUrl = $this->read('returnUrl');
    }
}
