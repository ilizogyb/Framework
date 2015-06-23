<?php
namespace Framework;

use \Framework\Request\Request;
use \Framework\Response\Response;
use \Framework\Router\Router;
use \Framework\Exception\ClassNotFoundException;

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
		//Вмикання Request і Response
		$request = new Request;
		$response = new Response;
		
		//Тестування виклику методів
		echo $request->getPathInfo().'<br>';
		echo $response->getStatusCode().'<br>';
		
		//Запуск процесу маршрутизації
		$routes = $this->config['routes'];
		$router = new Router($routes);
		
		echo $router->getController().'</br>';
		echo $router->getAction().'</br>';
		echo $router->getMethod().'</br>';
		echo $router->getId().'</br>';
		echo $router->getSecurity().'</br>';
	}


}