<?php
/**
 * Клас являє реалізацію базового класа додатку
 * @autor Lizogyb Igor
 * @since 1.0
 */

namespace Framework\Controller;
use \Framework\Renderer\Renderer;
use \Framework\Application;
use \Framework\Request\Request;
use \Framework\Response\Response;
use \Framework\Response\ResponseRedirect;
use \Framework\DI\Service;

abstract class Controller
{
	public $model;
	private $view;
    private $request;

    public function __construct()
    {

    }
    
    /**
     * Метод для візуалізації контенту
     * @param string $layout ім'я шаблону
     * @param array $params змінні для шаблону
     * @return string вміст сторінки
     */
    public function render($layout, $content)
    { 
        $renderer = new Renderer($layout, $content);
        return new Response($renderer->render());
    }
    
    /**
     * Метод для отримання поточного запиту
     * @return Request поточний запит
     *
     */
    public function getRequest()
    {
        return Service::get('request');
    }
    
    /**
     * Метод для реалізації функції перенаправлення
     * @param string $route роут для перенаправлення
     * @param string $message повідомлення користувачу
     * @return Object ResponseRedirect
     */
    public function redirect($route, $message = null)
    {
       return new ResponseRedirect($route);
    }
    
    /**
     * Метод для отримання поточного роута
     * @return string рядок з роутом
     */
    public function getRoute()
    { 
        echo $this->request->getPathInfo();
    }

    /**
     * Метод для створення заданого роута з параметрами
     * @param $route string рядок з роутом
     * @param $route string рядок з роутом
     * @return string рядок з заданим роутом
     */
    public function generateRoute($route, $params = array())
    {
        $router = Service::get('router');
        return $router->build($route, $params);
    }


}

