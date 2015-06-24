<?php

namespace Framework\Response;

/**
 * Клас реалізація HTTP Response
 * @autor Lizogyb Igor
 * @since 1.0
 *  
 */

class Response
{
	public static $httpStatuses = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        118 => 'Connection timed out',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        210 => 'Content Different',
        226 => 'IM Used',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Reserved',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        310 => 'Too many Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested range unsatisfiable',
        417 => 'Expectation failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable entity',
        423 => 'Locked',
        424 => 'Method failure',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        449 => 'Retry With',
        450 => 'Blocked by Windows Parental Controls',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway or Proxy Error',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        507 => 'Insufficient storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];
    
    private $_statusCode = 200;
    private $_headers = array('content-type' => 'text/html');
    public $content;
    public $statusText = 'OK';
    public $version;
	public $body = null;
		
    public function __construct()
	{
		if (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.0') {
			$this->version = '1.0';
        } else {
           $this->version = '1.1';
        }
	}
	
	/**
	* Метод для отримання статус-коду Response
	* @return рядок із значенням статус-коду
	*/
    public function getStatusCode()
    {
        return $this->_statusCode;
    }
	
	/**
	* Метод для встановлення статус-коду Response
	* @param string $value значення статус-коду
	* @param string $text текст статус-коду
	*/
    public function setStatusCode($value, $text = null)
    {
		if ($value === null) {
            $value = 200;
        }
         $this->_statusCode = (int) $value;

        if ($text === null) {
			 $this->statusText = isset(static::$httpStatuses[$this->_statusCode]) ? static::$httpStatuses[$this->_statusCode] : '';
        } else {
			$this->statusText = $text;
		}
	}
	
	/**
	* Метод для отримання заголовку Response
	* @param string $name рядок з іменем заголовку
	* @return рядок із значенням заголовку
	*/
	public function getHeader($name)
    {
        $name = strtolower($name[0].preg_replace('/([A-Z])/', '-$1', substr($name, 1)));
        return isset($this->_headers[$name]) ? $this->_headers[$name] : NULL;
    }
	
	/**
	* Метод для встановлення заголовку Response
	* @param string $name ім'я заголовку
	* @param string $value значення заголовку
	*/
	public function setHeader($name, $value)
    {
		$name = strtolower($name[0].preg_replace('/([A-Z])/', '-$1', substr($name, 1)));
        $this->_headers[strtolower(preg_replace('/([A-Z])/', '-$1', $name))] = $value;
    }
	
	/**
	* Метод для отримання усіх існуючих заголовків Response
	* @return  масив існючих заголовків
	*/
	public function getHeaders()
    {
		return $this->_headers;
	}




}
?>
