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
	protected $config;

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
		$request = new Request;
		$response = new Response;
		
		//Тестування виклику методів Response
        /*
		$response->setStatusCode(200,'OK');
		$response->setHeader('Content-Type','text/html; charset=UTF-8');
		$response->setCookie('test','Hello from framework',3600);
		$response->setContent('<h2>Тест встановлення куків</h2>');
		$response->setContent("<h3><script>document.write(document.cookie);</script></h3>");
		$response->setContent('<h2>Тест роботи з запитами</h2>');
		$response->setContent("<form action='' method='POST'><input type='text' name='user'><input type='submit' value='Send'></from>");
		//echo $response->__toString();
		$response->send();
*/
			
		//Запуск процесу маршрутизації
		$routes = $this->config['routes'];
		$router = new Router($request, $routes);
		$router->run();
		echo '<br>';
		echo $router->getController().'</br>';
        
        
       //Створюємо інстанс контроллера
        //$controllerName = $router->getController();
        //$controller = new $controllerName ;
        
        //Всі запити крім profile в якому декілька дій
        //if(count($router->getAction() === 1)) {
          //  $controllerAction = $router->getAction()[0] . 'Action';
       // }
        //Запуск на виконання  дій контроллера
        //call_user_func(array($controller, $controllerAction));
        
        
        echo '<br>';
		print_r($router->getAction());
        echo '</br>';
		echo $router->getMethod().'</br>';
        echo $router->getSecurity().'</br>';
		echo $router->getId().'</br>';
		//echo $router->getSecurity().'</br>';
        
        //Тест роботи з сесією
        $session = new Session('userTest');
        echo $session->start();
        $session->write('igor','Test');
        $session->shutdown();
        $session->start();
        echo "Session Value: " . $session->read('igor');
        echo '</br>';
        echo "Session ID: " . $session->getSessionId();
        //DI Tests 
        
        //aliase
         //Service::set('test','Framework\DITEst');
         Service::set('test',function(){
             $obj = new DITEst;
             return $obj;
         });
         Service::get('test');
        /*
        Service::set('Framework\DITEst');
        $test = Service::get('Framework\DITEst');
        $test->getText();
        */
 

       
       /// Тест контейнера на основі патерна ServiceLocator
       
       $serviceContainer = new ServiceLocator;
       $serviceContainer->set('r', new DITEst);
       $t = $serviceContainer->get('r');
       $t->getText();
       
       
       //Тест Exception
       /*
       $except = new FrameworkException("Exception test", 505, true);
       $se = new StorageException("Error stor", 505, true);
       $ce = new ConfigurationException("Error config", 505, true);
       $fe = new FileException("File not Found", 505, true);
       $hnfe = new HTTPNotFoundException("Not found", 404, true);
       */
	}


}