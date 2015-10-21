<?php
namespace Framework;

use \Framework\Request\Request;
use \Framework\Response\Response;
use \Framework\Router\Router;
use \Framework\Session\Session;
use \Framework\DI\Service;
use \Framework\DI\ServiceLocator;
use \Framework\Exception\ClassNotFoundException;
use \Framework\Exception\FrameworkException;
use \Framework\Exception\StorageException;
use \Framework\Exception\ConfigurationException;
use \Framework\Exception\FileException;
use \Framework\Exception\HTTPNotFoundException;

/**
* Application головний клас додатку
* створює об'єкти, запускає маршрутизацію, вмикає потрібні контроллери
* @autor Lizogyb Igor
* @since 1.0
*/

class Application
{
    const VIEW_PATH = '/src/Blog/views';
    protected $config;
    protected static $request;
    protected static $router;

	/**
	* Ініціалізація стану Application
	*
	*/
	
	public function __construct($param)
	{
		if(file_exists($param) && is_readable($param)) {
			$this->config = include($param);
		}
	}
	
	/**
	* Головний метод класу, в який вкладено функціонал додатку
	*
	*/
	public function run()
	{
        //Створення Request і Response
		self::$request = new Request;
		$response = new Response;
        
        //Запуск процесу маршрутизації
		$routes = $this->config['routes'];
		self::$router = new Router(self::$request, $routes);
		self::$router->run();
		
        //// Remove this
        echo '<br>';
		echo self::$router->getController().'</br>';
        //// Remove this
        
        //Створюємо інстанс контроллера
        $controllerName = self::$router->getController();
        $controller = new $controllerName ;
        
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
        call_user_func(array($controller, $controllerAction));
        
	}
    
    /**
     * Метод для отримання шляху до шаблонів
     * @return string рядок до папки з шаблонами
     */
    public static function getViewPath() 
    {
      return $path = $_SERVER['DOCUMENT_ROOT'] . self::VIEW_PATH;
       
    }


}