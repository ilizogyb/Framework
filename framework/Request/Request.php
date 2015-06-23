<?php 

namespace Framework\Request;

/**
 * Клас Request являється HTTP запитом
 * @author Igor Lizogyb 
 * @since 1.0
 */
class Request 
{
    private $_hostInfo;
    private $_pathInfo;     
    private $_url;
    private $_part = array();

    public function __construct($url = null)
    {   
        $url = $_SERVER['REQUEST_URI'];
        $this->_url = urldecode($url);
        $this->_part = array();
        foreach (explode('/', $this->_url) as $k => $v) {
            if (!empty($v)) {
                $v = explode(':', $v);            
                if (!isset($v[1])) {
                    $this->_part[] = $v[0];
                } else {
                    $this->_part[$v[0]] = implode(':', array_slice($v, 1));
                }
            }
        }
    }

    /**
    * Повертає метод поточного запиту GET, POST, HEAD, PUT, DELETE
    * @return Метод повертає рядок, наприклад, GET, POST, HEAD, PUT, DELETE.
    * Значення, що повертається перетворився у верхньому регістрі.
    */
    public function getMethod()
    {
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        } else {        
            return isset($_SERVER['REQUEST_METHOD']) ? strtoupper($_SERVER['REQUEST_METHOD']) : 'GET';    
        }
    }

    /*
    *Метод повертає істину, якщо тип запиту POST
    *@return булеве значення, в залежності від типу запиту 
    *
    */
    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    /**
    * Метод повертає істину, якщо тип запиту GET
    * @return булеве значення, в залежності від типу запиту 
    *
    */
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

    /**
    * Метод повертає істину, якщо тип запиту PUT
    * @return булеве значення, в залежності від типу запиту 
    *
    */
    public function isPut()
    {
        return $this->getMethod() === 'PUT';
    }

    /**
    * Метод повертає істину, якщо тип запиту DELETE
    * @return булеве значення, в залежності від типу запиту 
    *
    */
    public function IsDelete()
    {
        return $this->getMethod() === 'DELETE';
    }
    
    /**
    * Метод повертає масив із параметрами запиту
    * @return масив з параметрами запиту, якщо вони наявні або булеве значення хибності
    *
    */
    public function getQueryParam()
    {
        if(count($_GET) > 0) {
            return $_GET;
        } else {
            return false;        
        }
    }
    
    /**
    * Метод повертає рядок з типом захисту 
    * @return рядок http або https
    *
    */
    public function getHostSecure()
    {
        $scheme = isset($_SERVER['HTTP_SCHEME']) ? $_SERVER['HTTP_SCHEME'] : (
            (
         (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
           443 == $_SERVER['SERVER_PORT']
             ) ? 'https' : 'http'
 
            );
        return $scheme; 
    }
    
    /**
    * Метод повертає рядок з інформацією про хост 
    * @return рядок з інформацією про хост
    *
    */
    public function getHostInfo()
    {
        if (isset($_SERVER['HTTP_HOST'])) {
            $this->_hostInfo = $this->getHostSecure() . '://' . $_SERVER['HTTP_HOST'];
        }
        return $this->_hostInfo;
    }

    /**
    * Метод повертає порт сервера 
    * @return ціле число порт сервера
    *
    */
    public function getServerPort()
    {
        return (int) $_SERVER['SERVER_PORT'];
    }

    /**
    * Метод повертає базовий уніфікований локатор 
    * @return рядок уніфікований локатор
    *
    */
    public function getBaseUrl()
    {
        return $this->getHostSecure().'://'.implode('/', array_slice(
            explode('/', $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']), 0, -1)).'/';
    }

    /**
    * Метод повертає дані про шлях в запрошеному URL
    * @return рядок зі шляхом
    *
    */
    public function getPathInfo()
    {
       if(strlen($this->_pathInfo) == 0) 
           $this->_pathInfo  = implode('/', $this->_part);
       return $this->_pathInfo;
    }
    
    /**
    * Метод встановлює шлях в запиті
    * @param рядок зі шляхом який необхідно встановити
    *
    */
    public function setPathInfo($value)
    {
        $this->_pathInfo = ltrim($value, '/');
    }
    
    /**
    * Метод повертає параметри із запиту
    * @param $key рядок із ключем для пошуку
    * @param $default значення параметра
    * @param $type тип параметра 
    */
    public function get($key, $default = null, $type = null) {
        if (isset($this->_part[$key])) {
            if ($type) {
                if (!is_array($type)) {
                    return call_user_func_array(array('Type', 'to'.ucfirst($type)), array($this->_part[$key]));
                } else {
                    return call_user_func_array(array('Type', 'to'.ucfirst($type[0])), array($this->_part[$key])+$type[1]);
                }
            } else {
                return $this->_part[$key];

            }
        } else {
            if (is_object($default) && $default instanceof Exception) {
                throw $default;
            } else {
                return $default;
            }
        }
    }

    /**
    * Метод перевіряє наявність параметрів в запиті
    * @param $key рядок із ключем для пошуку
    *
    */
    public function has($key) {
        return isset($this->_part[$key]);
    }

    /**
    * Метод перевіряє чи запит являється запитом Ajax
    * @return булеве значення істиності того що запит є запитом Ajax
    *
    */
    public function isAjax() {
        if ($this->get('ajax') == 1) {
            return true;
        } elseif (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            return true;
        } else {
            return false;
        }
    }
}
?>
