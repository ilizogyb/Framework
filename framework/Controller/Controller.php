<?php

namespace Framework\Controller;

use \Framework\Renderer\Render;
use \Framework\Application;
use \Framework\Request\Request;

abstract class Controller
{
	public $model;
	private $view;
    private $request;

    public function __construct()
    {
        //$this->request = Application::getRequest();
    }
    
    public function render($view, $params = [])
    {

    }
    
    public function getView()
    {
 
    }
    
    public function getRequest()
    {
       
    }
   
    
    public function redirect($route, $message = null)
    {

    }

    public function getRoute()
    {
        echo $this->request->getPathInfo();
    }

    public function generateRoute($route)
    {

    }
    

    public function get($path, $param = [])
    {  

    }

}

