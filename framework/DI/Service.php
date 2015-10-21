<?php

namespace Framework\DI;
use ReflectionClass;

/**
 * Реалізація контейнера DI
 * 
 * Приклади використання
 * 
 * Service::set('Framework\Test');
 * $obj = Service::get('Framework\Test');
 *
 * Або використовуючи аліас
 * 
 * Service::set('test','Framework\Test');
 * $obj = Service::get('test');
 *
 * Із заданням параметрів через анонімну функцію
 *
 * Service::set('test',function(){
             $obj = new Test;
             $obj->setUserName('Viktor');
             return $obj;
         });
 * Service::get('Framework\Test');
 *
 *
 * @autor Lizogyb Igor
 * @since 1.0
 */
 
class Service
{
    private static $instance = null;
    private static $_definitions = [];

    private function __construct(){ }
    protected function __clone(){ }

    public static function getInstance(){
        if (is_null(self::$instance)) {
            self::$instance = new Service();
        }
        return self::$instance;
    }

    /**
     * Метод реєструє опис класу для контейнера
     * @param string $class ім'я класу
     * @param string|array|callable $definition опис класу
     * @return $this цей контейнер
     */
    public static function set($class, $definition = [])
    {
        self::$_definitions[$class] = self::normalizeDefinition($class, $definition);
        return new self();
    }
   
    /**
     * Метод Нормалізує опис класу
     * @param string $class ім'я класу
     * @param string|array|callable $definition опис класу
     * @return array нормалізований опис класу  
     */
    protected static function normalizeDefinition($class, $definition)
    {
        if(empty($definition)) {
            return ['class' => $class];
        } elseif(is_string($definition)) {
            return ['class' => $definition];
        } elseif(is_callable($definition, true)) {
            return $definition;
        }

    }
    
    /**
     * Метод повертає залежності потрібного класу
     * @param string $class ім'я класу або аліасу
     * @return array масив з залежностями вказаного класу
     */
    protected static function getDependencies($class) {
       
        $dependencies = [];
        $reflection = new ReflectionClass($class);

        return [$dependencies, $reflection];
    }
    
    /**
     * Метод повертає інстанс потрібного класу
     * @param string $class ім'я класу або аліасу
     * @return object інстанс потрібного класу
     */
    public static function get($class)
    { 
        if (!isset(self::$_definitions[$class])) {
            return self::build($class);
        }
        
        $definition = self::$_definitions[$class];
        
        if (is_array($definition)) {
            $concrete = $definition['class'];
            unset($definition['class']);
            
            if ($concrete === $class) {
                $object = self::build($class);
            } else {
                $object = self::get($concrete);
            }
        } 
        
        if (is_callable($definition, true)) {
             $object = call_user_func($definition);
        }
        
          return $object;
    }
    
    /**
     * Метод повертає значення, що вказує чи має контейнер опис 
     * потрібного імені 
     * @param string $class ім'я класу або аліасу
     * @return boolean булеве значення істиності того чи має 
     * контейнер потрібний опис імені
     */
    public static function has($class)
    {
        return isset(self::$_definitions[$class]);
    }

    /**
     * Повертає об'єкт потрібного класу
     * Метод вирішує залежності зазначеного класу і 
     * встановлює їх у екземпляр класу
     * @param string ім'я класу
     * @return object новостворений екзеапляр класу
     */
    protected static function build($class)
    {
        list ($dependencies, $reflection) = self::getDependencies($class);
        return $reflection->newInstanceArgs($dependencies);
    }
}

