<?php
class Loader
{
    // карта для соответствия неймспейса пути в файловой системе
    protected $namespacesMap = array();
   
    public function register()
    {
        spl_autoload_register(array($this,'loadClass'));
    }

    public function unregister()
    {
        spl_autoload_unregister(array($this, 'loadClass'));    
    }

    public function addNamespacePath($namespace, $rootDir)
    {
        if (is_dir($rootDir)) {
			$namespace = trim($namespace, '\\');
            $this->namespacesMap[$namespace] = $rootDir;
            return true;
        }
        
        return false;
    }

    protected function loadClass($class)
    {
        $pathParts = explode('\\', $class);
        if(is_array($pathParts)) {
            $namespace = array_shift($pathParts);
            if (!empty($this->namespacesMap[$namespace])) {
                $filePath = $this->namespacesMap[$namespace] . '/' . implode('/', $pathParts) . '.php';
				require_once $filePath;
                return true;            
            }
        }
        return false;
    }
    
}

