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
		
		//Тестування виклику методів Response
		$response->setStatusCode(200,'OK');
		$response->setHeader('Content-Type','text/html; charset=UTF-8');
		$response->setCookie('test','Hello from framework',3600);
		$response->setContent('<h2>Тест встановлення куків</h2>');
		$response->setContent("<h3><script>document.write(document.cookie);</script></h3>");
		$response->setContent('<h2>Тест роботи з запитами</h2>');
		$response->setContent("<form action='' method='POST'><input type='text' name='user'><input type='submit' value='Send'></from>");
		//echo $response->__toString();
		$response->send();

		//Тестування виклику Request
		echo '<br>Шлях: ';
		echo $request->getPathInfo().'<br>';
		echo 'Метод: ' . $request->getMethod().'<br>';
		echo 'Ключі та параметри запиту: <br>';
		print_r($request->getQueryParams());
		echo '<br>Ключі запиту: <br>';
		print_r($request->getQueryParamKeys());
		echo '<br> GET для ?а = ' . $request->getQueryParam('a');
				
		//Запуск процесу маршрутизації
		$routes = $this->config['routes'];
		$router = new Router($routes);
		
		//echo $router->getController().'</br>';
		//echo $router->getAction().'</br>';
		//echo $router->getMethod().'</br>';
		//echo $router->getId().'</br>';
		//echo $router->getSecurity().'</br>';
	}


}