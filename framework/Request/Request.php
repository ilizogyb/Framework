<?php 
/**
 * Клас Request являється HTTP запитом
 * @author Igor Lizogyb 
 * @since 1.0
 */
 
namespace Framework\Request;

class Request 
{
    private $_hostInfo;
    private $_pathInfo;     
    private $_url;
    private $_part = array();

    public function __construct($url = null)
    {   if($url === null) {
            $url = $_SERVER['REQUEST_URI'];
        }
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
     * Метод повертає URL запиту
     * @return string рядок уніфікований локатор
     *
     */
    public function getUrl()
    {
        return $this->_url;
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
     *@return boolean булеве значення, в залежності від типу запиту 
     *
     */
    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * Метод повертає істину, якщо тип запиту GET
     * @return boolean булеве значення, в залежності від типу запиту 
     *
     */
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * Метод повертає істину, якщо тип запиту PUT
     * @return boolean булеве значення, в залежності від типу запиту 
     *
     */
    public function isPut()
    {
        return $this->getMethod() === 'PUT';
    }

    /**
     * Метод повертає істину, якщо тип запиту DELETE
     * @return boolean булеве значення, в залежності від типу запиту 
     *
     */
    public function IsDelete()
    {
        return $this->getMethod() === 'DELETE';
    }
    
    /**
     * Метод повертає масив із параметрами запиту
     * @return string масив з параметрами запиту, 
     * якщо вони наявні або булеве значення хибності
     *
     */
    public function getQueryParams()
    {
        if($this->isGet()) {
		    if(count($_GET) > 0) {
                return $_GET;
            } else {
                return false;        
            }
		}
		if($this->isPost()) {
		    if(count($_POST) > 0) {
                return $_POST;
            } else {
               return false;        
            }
		}
    }
	
	/**
     * Метод повертає масив із ключами параметрів запиту
     * @return string масив з ключами параметрів запиту, 
     *
     */
	public function getQueryParamKeys()
    {
		if($this->isGet()) {
			return array_keys($_GET);
		}
		if($this->isPost()) {
			return array_keys($_POST);
		}
    }
    /**
     * Метод для отримання певного параметру із запиту
     * @param string $name  рядок із назвою параметра 
     * @return string рядок із значенням параметра запиту, 
     * якщо він наявний або булеве значення хибності
     *
     */
    public function getQueryParam($name)
    {
        if($this->isGet()) {		
            if(count($_GET) > 0 && strlen($name) > 0) {
			    if(array_key_exists($name, $_GET)) 
                    return $this->filter($_GET[$name]);
            } else {
                return false;        
            }
        }
        if($this->isPost()) {
		    if(count($_POST) > 0 && strlen($name) > 0) {
			    if(array_key_exists($name, $_POST)) 
                    return $this->filter($_POST[$name]);
            } else {
                return false;        
            }	
		}			
	}
    
    /**
     * Метод повертає рядок з типом захисту 
     * @return string рядок http або https
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
     * @return string рядок з інформацією про хост
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
    * @return int ціле число порт сервера
    *
    */
    public function getServerPort()
    {
        return (int) $_SERVER['SERVER_PORT'];
    }

    /**
     * Метод повертає базовий уніфікований локатор 
     * @return string рядок уніфікований локатор
     *
     */
    public function getBaseUrl()
    {
        return $this->getHostSecure().'://'.implode('/', array_slice(
            explode('/', $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF']), 0, -1)).'/';
    }

    /**
     * Метод повертає дані про шлях в запрошеному URL
     * @return string рядок зі шляхом
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
     * @param string $value рядок зі шляхом який необхідно встановити
     *
     */
    public function setPathInfo($value)
    {
        $this->_pathInfo = ltrim($value, '/');
    }
    
    
    /**
     * Метод повертає параметри із запиту методом GET
     * @param string $key рядок із ключем для пошуку
     * @param string $default значення параметра
     */  
    public function get($key,$default = null) 
    {
        if($this->isGet()) {
           $result = isset($_GET[$key]) ? $this->filter($_GET[$key]) : $default;
           return $result;
        }
    }
    
    /**
     * Метод повертає параметри із запиту методом POST
     * @param string $key рядок із ключем для пошуку
     * @param string $default значення параметра
     */   
    public function post($key, $default = null)
    {
        if($this->isPost()) {
            $result = isset($_POST[$key]) ? $this->filter($_POST[$key]) : $default;
            return $result;
        }
    }

    /**
     * Метод перевіряє наявність параметрів в запиті
     * @param string $key рядок із ключем для пошуку
     *
     */
    public function has($key) {
        return isset($this->_part[$key]);
    }

    /**
    * Метод перевіряє чи запит являється запитом Ajax
    * @return boolean булеве значення істиності того що запит є запитом Ajax
    *
    */
    public function isAjax() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Метод виконує фільтрацію вхідних даних запиту
     * @param string $value вхідні данні для фільтрації
     * @return string $text відфільтровані дані 
     *
     */
    protected function filter($value)
    {
        if (strlen($value)){
            $text = trim($value);
            $pattern = '/<\s*\/*\s*\w*>|[\$`~#<>\[\]\{\}\\\*\^%]/';
            $text = preg_replace($pattern, "", $text);
            $text = htmlspecialchars($text);
        } else {
            return false;
        }
        return $text;
    }
}
?>
