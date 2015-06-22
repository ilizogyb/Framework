<?php
namespace Framework;

class Application
{
	protected $config;
	
	public function __construct($param)
	{
		if(file_exists($param) && is_readable($param)) {
			$this->config = include($param);
		}
	}
	
	public function run()
	{
		//Подключение реквеста
		$request = new \Framework\Request\Request;
		echo $request->getPathInfo();
		//Включаем процесс роутинга
		$request = new \Framework\Request\Request;
		$routes = $this->config['routes'];
		$router = new \Framework\Router\Router($routes);
		echo $router->getController().'</br>';
		echo $router->getAction().'</br>';
		echo $router->getMethod().'</br>';
		echo $router->getId().'</br>';
		echo $router->getSecurity().'</br>';
		
	}


}