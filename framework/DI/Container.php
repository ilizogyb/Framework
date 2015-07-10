<?php

namespace Framework\DI;
use ReflectionClass;

/**
 * Реалізація контейнера DI
 * 
 * Приклади використання
 * 
 * $container = new Container;
 * $container->set('Framework\Test');
 * $obj = $container->get('Framework\Test');
 *
 * Або використовуючи аліас
 * 
 * $container = new Container;
 * $container->set('test','Framework\Test');
 * $obj = $container->get('test');
 *
 * Із заданням параметрів через анонімну функцію
 *
 * $container = new Container;
 * $container->set('test',function(){
             $obj = new Test;
             $obj->setUserName('Viktor');
             return $obj;
         });
 * $obj = $container->get('Framework\Test');
 *
 *
 * @autor Lizogyb Igor
 * @since 1.0
 */
class Container
{
    private $_definitions = [];

    /**
     * Метод реєструє опис класу для контейнера
     * @param string $class ім'я класу
     * @param string|array|callable $definition опис класу
     * @return $this цей контейнер
     */
    public function set($class, $definition = [])
    {
        $this->_definitions[$class] = $this->normalizeDefinition($class, $definition);
        return $this;
    }
   
    /**
     * Метод Нормалізує опис класу
     * @param string $class ім'я класу
     * @param string|array|callable $definition опис класу
     * @return array нормалізований опис класу  
     */
    protected function normalizeDefinition($class, $definition)
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
    protected function getDependencies($class) {
       
        $dependencies = [];
        $reflection = new ReflectionClass($class);

        return [$dependencies, $reflection];
    }
    
    /**
     * Метод повертає інстанс потрібного класу
     * @param string $class ім'я класу або аліасу
     * @return object інстанс потрібного класу
     */
    public function get($class)
    {
        if (!isset($this->_definitions[$class])) {
            return $this->build($class);
        }
        
        $definition = $this->_definitions[$class];
        
        if (is_array($definition)) {
            $concrete = $definition['class'];
            unset($definition['class']);
            
            if ($concrete === $class) {
                $object = $this->build($class);
            } else {
                $object = $this->get($concrete);
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
    public function has($class)
    {
        return isset($this->_definitions[$class]);
    }

    /**
     * Повертає об'єкт потрібного класу
     * Метод вирішує залежності зазначеного класу і 
     * встановлює їх у екземпляр класу
     * @param string ім'я класу
     * @return object новостворений екзеапляр класу
     */
    protected function build($class)
    {
        list ($dependencies, $reflection) = $this->getDependencies($class);
        
        return $reflection->newInstanceArgs($dependencies);
    }
}

