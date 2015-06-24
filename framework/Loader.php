<?php

use \Framework\Exception\ClassNotFoundException;

/**
* Реалізація автозавантаження класів
* @autor Lizogyb Igor
* @since 1.0
*/
class Loader
{
    //карта для відповідності неймспейсу шляху в файловій системі
    protected $namespacesMap = array();
   
    /**
	* Реєстрація власного автозавантажувача в стек автозавантаження
	*
	*/
    public function register()
    {
        spl_autoload_register(array($this,'loadClass'));
    }
	
    /**
	* Видалення власного автозавантажувача із стеку автозавантаження
	*
	*/
    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));    
    }
	
    /**
	* Встановлення шляху простору імен
	* @param string $namespace рядок із значенням простору імен
	* @param string $rootDir рядок із значенням шляху
	* @return булеве значення істиності або хибності в залежності від результату роботи методу
	*/
    public function addNamespacePath($namespace, $rootDir)
    {
        if (is_dir($rootDir)) {
			$namespace = trim($namespace, '\\');
            $this->namespacesMap[$namespace] = $rootDir;
            return true;
        }
        
        return false;
    }
	
    /**
	* Завантаження потрібного класу
	* @param string $class ім'я класу для завантаження
	* @return булеве значення істиності або хибності в залежності від результату роботи методу
	* @throws ClassNotFoundException якщо клас не знайдено
	*/
    protected function loadClass($class)
    {
        $pathParts = explode('\\', $class);
        if(is_array($pathParts)) {
            $namespace = array_shift($pathParts);
            if (!empty($this->namespacesMap[$namespace])) {
                $filePath = $this->namespacesMap[$namespace] . '/' . implode('/', $pathParts) . '.php';
				if(file_exists($filePath)) {
					require_once $filePath;
				} else {
					throw new ClassNotFoundException($class.' not found in : '.$filePath);
				}
                return true;
            } else {
				
			}
        }
        return false;
    }
}

