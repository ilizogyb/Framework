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
use \Framework\Response\ResponseRedirect;
use \Framework\Router\Router;
use \Framework\Renderer\Renderer;
use \Framework\DI\Service;
use \Framework\Session\Session;
use \Framework\Model\ActiveRecord;
use \Framework\Model\Config;
use \Framework\Model\Connection;
use \Framework\Security\Security;
use \CMS\Controller\ProfileController;

class Application
{
    const VIEW_PATH = '../src/Blog/views';
    protected $config;
    private $dsn;
    
	/**
	* Ініціалізація стану Application
	* @param string array параметри для додатку
	*/
	
	public function __construct($param)
	{
		if(file_exists($param) && is_readable($param)) {
			$this->config = include($param);
		}
        
        $services = Service::getInstance();
        $services::set('session', function(){
            $obj = Session::getInstance();
            $obj->start();
            return $obj;
        });
        Service::set('request', '\Framework\Request\Request');
        $services::set('router', function(){
            $obj = new Router($this->config['routes']);
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
        $request = Service::get('request');
        $router = Service::get('router')->run();
        
        $controller = $router['controller'];
        
        $action = $router['action'].'Action';
        //echo $action;
      
        $vars = null;
        if(!empty($router['vars'])) {
            $vars = $router['vars'];
        }
        
        if(!empty($router['security'])){
            $user = Service::get('session')->read('user');

            if(isset($user)) {
                if (is_object($user)) {
                    $role = get_object_vars($user);
                }
                if (array_search($role['role'], $router['security']) === false){
                    new Exception('access denied');
                    die;
                }
            } else {
                $redirect = new ResponseRedirect(Service::get('router')->build('login'));
                $redirect->send();
            }
        }

        Service::get('session')->setReturnUrl($request->getBaseUrl());

        $response =  $this->runController($controller, $action, $vars);
        
        if ($response instanceof Response){
             $view = $this->config['main_layout'];
             $renderer = new Renderer($view, array('content' => $response->getContent()));
             $content = $renderer->render();
             $response = new Response($content);
             $response->send();
        }
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
    

    protected function runController($controller, $controllerAction, $vars)
    {
        if (class_exists($controller))
        {
            $controller = new $controller;
            $refl = new \ReflectionClass($controller);
            if ($refl->hasMethod($controllerAction))
            {
                $method = new \ReflectionMethod($controller, $controllerAction);
                $params = $method->getParameters();
                
                if(empty($params))
                {
                    $response = $method->invoke(new $controller);
                } else {
                    $response = $method->invokeArgs(new $controller, $vars);
                } 
            } else { 
                throw new \Exception('Parameters not found'); 
            }
            
        } else { 
            throw new \Exception('Class ' . $controller . ' not found '); 
        }
        return $response;
    }
}