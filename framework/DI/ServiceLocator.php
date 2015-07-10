<?php

namespace Framework\DI;

 /**
  * Реалізація контейнера сервісів на основі патерна ServiceLocator 
  *  
  * Приклади використання
  *
  * $serviceContainer = new ServiceLocator;
  * $serviceContainer->set('test', '\Framework\Test');
  * $service =  $serviceContainer->get('test');
  * 
  * Або
  *
  * $serviceContainer = new ServiceLocator;
  * $serviceContainer->set('test', new Test);
  * $service =  $serviceContainer->get('test');
  *
  * @autor Lizogyb Igor
  * @since 1.0
  */
  
class ServiceLocator
{
		private $services;
		private $instantiated;
		private $shared;
		
		public function __construct() 
		{
			$this->services = array();
			$this->instantiated = array();
			$this->shared = array();
		}
        
		/**
         * Метод для додавання сервісу в  контейнер
         * @param string $name назва сервісу
         * @param string $service опис сервісу
         * @param string $share доступність 
         */
		public function set($name, $service, $share = true) 
		{
			if(is_object($service) && $share) {
			    $this->instantiated[$name] = $service;
			}
			$this->services[$name] = (is_object($service) ? get_class($service) : $service);
			$this->shared[$name]   = $share;
		}
        
		/**
         * Метод для перевірки існування сервісу в контейнері
         * @param string $name назва сервісу
         * @return boolean булеве значення істиності якщо сервіс наявний
         */
		public function has($name)
		{
			return (isset($this->instantiated[$name]) || isset($this->services[$name]));
		}
		        
		/**
         * Метод для отримання сервісу з контейнера
         * @param string $name назва сервісу
         * @return потрібний сервіс
         */
		public function get($name) 
		{
		    // Отримує екземпляр, якщо він існує, і доступний
		    if(isset($this->instantiated[$name]) && isset($this->services[$name])) {
				return $this->instantiated[$name];
			}
			// В іншому випадку створюєм
			$service = $this->services[$name];
			
			$object = new $service();
			
			if ($this->shared[$name]) {
				$this->instantiated[$name] = $object;
			}
			
			return $object;
		}
}
?>
