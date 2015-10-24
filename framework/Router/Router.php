<?php
/**
* Клас Router являється реалізацією маршрутизатора
* @autor Lizogyb Igor
* @since 1.0
*/

namespace Framework\Router;

use Framework\DI\Service;
use Framework\Exception\RouteException;

class Router 
{
   protected $request;
   protected $map = array();
   
   public function __construct($map = array()){
        $this->map = $map;
        $this->request = Service::get('request');
    }
    /**
     * Головний метод класу, який запускає процес маршрутизації
     * шукає та повертає контроллер, дію та параметри(змінні)
     * @return mixed
     *
     */
    public function run() 
    {
        $url =  $this->request->getUrl();
        if(!is_null($this->map))
        {
            foreach ($this->map as $key=>$value)
            {
                if (strpos($value['pattern'], '{')) {
                   $result = $this->patternToRegexp($value);
                   $pattern = $result[0];
                   $vars = $this->getVars($pattern, $result[1], $url);
                } else {
                    $pattern = $pattern = $value['pattern'];
                }
                
                if(preg_match('~^'.$pattern.'$~', $url))
                {
                    $routes = $value;
                    
                    if(!isset($value['_requirements']['_method'])) {
                        break;
                    }
                }

            }  
            if(!empty($routes))
            {
                if(!empty($vars)) {
                    $routes['vars'] = $vars;
                }   
                return $routes;

            } else { 
                throw new RouteException('Request '. $this->request->getBaseUrl() . $this->request->getPathInfo() .' is bad Request', 400);
            }
        } else {
            throw new RouteException();
        }
    }
    
    /**
     * Метод для отримання патерну для розбору роутів
     * @param string array $routes масив з потрібним патерном, контроллером
     * дією, та змінними
     * @return mixed 
     */
    private function patternToRegexp($routes = array())
    { 
        $pattern = '/\{[\w\d_]+\}/Ui';
        preg_match_all($pattern, $routes['pattern'], $matches);
        foreach ($matches[0] as $value){
            if(array_key_exists(trim($value, '{}'), $routes['_requirements'])) {
                $replacement[] = '('.$routes['_requirements'][trim($value, '{}')].')';
            }
            $str = str_replace($matches[0], $replacement, $routes['pattern']);
            return array($str, $matches[0]);
        }

    }
    
    /**
     * Метод для отримання зміних із запиту
     * @param string $pattern патерн для порівняння
     * @param string array $keys масив з ключами які потрібно порівняти
     * @param string $url поточний URL
     * @return mixed 
     */
    private function getVars($pattern, $keys, $url)
    {
        $vars = array();
        preg_match('~'.$pattern.'~i', $url, $matches);
        foreach ($keys as $key=>$value){
            if (isset($matches[$key+1])){
                $vars[trim($value, '{}')] = $matches[$key+1];
            }
        }
        return $vars;
    }
    
    /**
     * Метод для побудови роута по заданим значенням
     * @param string $name
     * @param string  array $param параметри роута
     * @param string array $params параметри роута
     */
    public function build($name, $params = array())
    {
        $url = '';
        if(array_key_exists($name, $this->map)){
            $url = $this->map[$name]['pattern'];
            if($params){
                foreach($params as $key=>$value) {
                    $url = str_replace('{'.$key.'}', $value, $url);
                }
            }
            $url = preg_replace('~\{[\w\d_]+\}~iU', '', $url);
        } 
        return $url;
    }
}