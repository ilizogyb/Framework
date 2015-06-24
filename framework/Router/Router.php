<?php

namespace Framework\Router;

/**
* Клас Router являється реалізацією маршрутизатора
* @autor Lizogyb Igor
* @since 1.0
*/
class Router 
{

	const DEFAULT_CONTROLLER = "Blog\\Controller\\PostController";
	const DEFAULT_ACTION     = "index";
	
	protected $controller    = self::DEFAULT_CONTROLLER;
    protected $action        = self::DEFAULT_ACTION;
	protected $method        = '';
	protected $security      = '';
	protected $id            = '';
	protected $basePath      = "/";

	public function __construct($options)
	{
		$this->parseUri($options);
	}
	
	/** 
	* Метод для отримання URI
	* @ return масив з частинами URI
	*/
	protected function getUri()
	{
		if(!empty($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
			$array_uri = explode('/',$uri);
			return $array_uri;
		}
	
	}
	
	/** 
	* Метод для розбору URI
	* @param array $data_array масив з конфігурацією маршрутів та їх
	* властивостей(параметрів)
	*/
	protected function parseUri(array $data_array)
	{
		$uri_array = $this->getUri();
		foreach($data_array as $data) {
			$result = explode('/',$data['pattern']);
			
			//Обробка URI виду /
			if(strlen($uri_array[1]) === 0) {
				break;
			}
			//Обробка URI виду /resource
			if(count($uri_array) === 2 && count($result) === 2) {
				if($uri_array[1] == $result[1]) {
					$this->controller = $data['controller'];
					$this->action = $data['action'];
					//Обообка вкладених опцій
					if(isset($data['_requirements']) && is_array($data['_requirements']))
					{
						foreach($data['_requirements'] as $k=>$v) {
							if($k === '_method') {
								$this->method = $v;
							}
							if($k === 'id') {
								$this->id = $v;
							}
						}
					}
				}
			}
			//Обробка URI виду /resource/resource1
			if(count($uri_array) === 3 && count($result) === 3) {
				//Обробка URI виду /resource/id
				if (!preg_match_all("/[^\d+$]/", $uri_array[2])){
					$id = $uri_array[2];
					$uri_array[2] ='{id}';					
				}

				if($uri_array[1] == $result[1] && $uri_array[2] == $result[2]) {
					$this->controller = $data['controller'];
					$this->action = $data['action'];
					//Обробка вкладених опцій
					if(isset($data['security']) && is_array($data['security'])) {
						$this->security = $data['security'][0];
					}
					if(isset($data['_requirements']) && is_array($data['_requirements']))
					{
						foreach($data['_requirements'] as $k=>$v) {
							if($k === 'id') {
								$this->id = $id;
							}
						}
					}
				}
			}
			//Обробка URI виду /resource/resource1/resource2
			if(count($uri_array) === 4 && count($result) === 4) {
				//Перевірка URI виду /resource/id
				if (!preg_match_all("/[^\d+$]/", $uri_array[2])){
					$id = $uri_array[2];
					$uri_array[2] ='{id}';					
				}
				
				if($uri_array[1] == $result[1] && $uri_array[2] == $result[2] && $uri_array[3] == $result[3]) {
					$this->controller = $data['controller'];
					$this->action = $data['action'];
					//Обробка вкладених опцій
					if(isset($data['security']) && is_array($data['security'])) {
						$this->security = $data['security'][0];
					}
					if(isset($data['_requirements']) && is_array($data['_requirements']))
					{
						foreach($data['_requirements'] as $k=>$v) {
							if($k === 'id') {
								$this->id = $id;
							}
							if($k === '_method') {
								$this->method = $v;
							}
						}
					}
				}
			}
				
			
		}
	}
	
	/**
	* Отримання контроллера
	* @return Рядок із значенням контроллера
	*/
	public function getController()
	{
		return $this->controller;
	}
	
	/**
	* Отримання дії
	* @return Рядок із значенням дії
	*/	
	public function getAction()
	{
		return $this->action;
	}
	
	/**
	* Отримання методу
	* @return Рядок із значенням методу або булеве 
	* значення хибності якщо метод не існує
	*/
	public function getMethod()
	{
		if(strlen($this->method) > 0)
			return $this->method;
		else 
			return false;
	}
	
	/**
	* Отримання Id посту
	* @return Рядок із значенням Id посту або булеве 
	* значення хибності якщо метод не існує
	*/	
	public function getId()
	{
		if(strlen($this->id) > 0)
			return $this->id;
		else
			return false;
	}
	
	/**
	* Отримання властивостей безпеки
	* @return Рядок із значенням властивостей безпеки або булеве 
	* значення хибності якщо метод не існує
	*/
	public function getSecurity()
	{
		if(strlen($this->security) > 0)
			return $this->security;
		else
			return false;
	}
	
	/**
	* Метод вмикання потрібного контроллера
	*/
	function run()
	{
		// Подключаем файл контроллера, если он имеется
        $controllerFile = ROOT.'/src/'.$this->controller.'.php';
		if(file_exists($controllerFile)){
        //  include($controllerFile);
			echo $controllerFile;
        }
	   
	}
}

