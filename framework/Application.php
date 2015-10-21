<?php
/**
* Application головний клас додатку
* створює об'єкти, запускає маршрутизацію, вмикає потрібні контроллери
* @autor Lizogyb Igor
* @since 1.0
*/

namespace Framework;

use \Framework\Request\Request;
use \Framework\Response\Response;
use \Framework\Router\Router;
use \Framework\Renderer\Render;
use \Framework\DI\Service;
use \Framework\Session\Session;
use \Framework\Model\ActiveRecord;
use \Framework\Model\Config;
use \Framework\Model\Connection;
use Framework\Security\Security;


class Application
{
    const VIEW_PATH = '../src/Blog/views';
    protected $config;
    protected static $request;
    protected static $router;
    private $dsn;
    protected static $DBconnect;
    
	/**
	* Ініціалізація стану Application
	* @param string array параметри для додатку
	*/
	
	public function __construct($param)
	{
		if(file_exists($param) && is_readable($param)) {
			$this->config = include($param);
		}
        
        self::$request = new Request;
        $services = Service::getInstance();
        $services::set('session', function(){
            $obj = Session::getInstance();
            $obj->start();
            return $obj;
        });
        $services::set('router', function(){
            $obj = new Router(self::$request, $this->config['routes']);
            return $obj;
        });
        $services::set('security', '\Framework\Security\Security');
	}
	
	/**
	* Головний метод класу, в який вкладено функціонал додатку
	*
	*/
	public function run()
	{
		//Запуск процесу маршрутизації
		self::$router = Service::get('router');
        self::$router->run();

        //Створюємо інстанс контроллера
        $controllerName = self::$router->getController();
        $controller = new $controllerName;
        
        //Обробка даних з роутера та виклик потрібного контроллера і передача дій
        if(count(self::$router->getAction()) === 1) {
            $controllerAction = self::$router->getAction()[0] . 'Action';
        } 

        if(count(self::$router->getAction()) === 2) {
            if(self::$request->isPost()) {
                echo $controllerAction = self::$router->getAction()[0] . 'Action';
            } elseif(self::$request->isGet()) {
                echo $controllerAction = self::$router->getAction()[1] . 'Action';
            }
        }
        
        //Запуск на виконання  дій контроллера
        //Отримуємо із запиту id з номером посту
        if(self::$router->getId()) {
            call_user_func(array($controller, $controllerAction), self::$router->getId());
        } else {
           call_user_func(array($controller, $controllerAction));
        }

        Service::get('session')->setReturnUrl(self::$request->getBaseUrl());
    }
    
    /**
     * Метод для створення підключення до бази данних
     * @return PDO Object Connection 
     */   
    public static function createConnection() 
    {
        $config = Config::getInstance();
        $config->setConnections(array('test'=>'mysql:host=127.0.0.1;dbname=mytest;'));
        $config->setDefaultConnection('test');
        $conn = new Connection();
        return $conn->createConnection();
    }
    
    /**
     * Метод для отримання шляху до шаблонів
     * @return string рядок до папки з шаблонами
     */
    public static function getViewPath() 
    {
      return $path = $_SERVER['DOCUMENT_ROOT'] . self::VIEW_PATH;
       
    }
    
    /**
     * Метод для отримання рендера
     * @return Render
     */
    public static function getView()
    {
        return new Render(self::getViewPath());
    }
    
    /**
     * Метод отримання поточного реквеста
     * @return Request інстанс поточного реквесту
     *
     */
    public static function getRequest()
    {
        return self::$request;
    }
    
    /**
     * Метод для виклику потрібного контроллера і його методів
     * Якщо список параметрів порожній то створюється об'єкт потрібного
     * контроллера і передається в місце виклику
     * @param string $uri роут портрібного контроллера, наприклад 'news'
     * @param array string $param масив з параметрами, наприклад ['action'=>'index']
     * @return Об'єкт потрібного контроллера
     */
    public static function runController($uri, $param = [])
    {
        $req = new Request($uri);
        self::$router->setUri($req);
        $controllerName = self::$router->getController();
        $controllerObj = new $controllerName();

        if (array_key_exists('action', $param)) {
            $controllerAction = $param['action'] . 'Action';
            call_user_func(array($controllerObj, $controllerAction));
        } else {
            return $controllerObj;
        }
    }
}