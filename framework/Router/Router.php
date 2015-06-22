<?php

namespace Framework\Router;

class Router {

	const DEFAULT_CONTROLLER = "Blog\\Controller\\PostController";
	const DEFAULT_ACTION     = "index";
	
	protected $controller    = self::DEFAULT_CONTROLLER;
    protected $action        = self::DEFAULT_ACTION;
	protected $method        = '';
	protected $security      = '';
	protected $id            = '';
	protected $basePath      = "/";

	public function __construct($options) {
		$this->parseUri($options);
	}
	
	//Получаем URI.
	protected function getUri() {
		if(!empty($_SERVER['REQUEST_URI'])) {
			$uri = $_SERVER['REQUEST_URI'];
			$array_uri = explode('/',$uri);
			return $array_uri;
		}
	
	}
	//Парсим URI
	protected function parseUri(array $data_array) {
		$uri_array = $this->getUri();
		foreach($data_array as $data) {
			$result = explode('/',$data['pattern']);
			//Обработка URI вида /
			if(strlen($uri_array[1]) === 0) {
				break;
			}
			//Обработка URI вида /resource
			if(count($uri_array) === 2 && count($result) === 2){
				if($uri_array[1] == $result[1]) {
					$this->controller = $data['controller'];
					$this->action = $data['action'];
					//Обработка вложеных опций
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
			//Обработка URI вида /resource/resource1
			if(count($uri_array) === 3 && count($result) === 3) {
				//Проверка URI вида /resource/id
				if (!preg_match_all("/[^\d+$]/", $uri_array[2])){
					$id = $uri_array[2];
					$uri_array[2] ='{id}';					
				}

				if($uri_array[1] == $result[1] && $uri_array[2] == $result[2]) {
					$this->controller = $data['controller'];
					$this->action = $data['action'];
					//Обработка вложеных опций
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
			//Обработка URI вида /resource/resource1/resource2
			if(count($uri_array) === 4 && count($result) === 4) {
				//Проверка URI вида /resource/id
				if (!preg_match_all("/[^\d+$]/", $uri_array[2])){
					$id = $uri_array[2];
					$uri_array[2] ='{id}';					
				}
				
				if($uri_array[1] == $result[1] && $uri_array[2] == $result[2] && $uri_array[3] == $result[3]) {
					$this->controller = $data['controller'];
					$this->action = $data['action'];
					//Обработка вложеных опций
					if(isset($data['security']) && is_array($data['security'])) {
						$this->security = $data['security'][0];
					}
					if(isset($data['_requirements']) && is_array($data['_requirements']))
					{
						foreach($data['_requirements'] as $k=>$v) {
							if($k === 'id') {
								//$this->id = $v;
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
	
	public function getController() {
		return $this->controller;
	}
	
	public function getAction() {
		return $this->action;
	}
	
	public function getMethod() {
		if(strlen($this->method) > 0)
			return $this->method;
		else 
			return false;
	}
	
	public function getId() {
		if(strlen($this->id) > 0)
			return $this->id;
		else
			return false;
	}
	
	public function getSecurity() {
		if(strlen($this->security) > 0)
			return $this->security;
		else
			return false;
	}

	function run(){
		// Подключаем файл контроллера, если он имеется
        $controllerFile = ROOT.'/src/'.$this->controller.'.php';
		if(file_exists($controllerFile)){
        //    include($controllerFile);
		echo $controllerFile;
        }
	   
	}
}

