<?php
namespace Framework\Exception;
use Framework\Response\Response;

/**
 * Реалізація базового класу для обробки помилок
 * @autor Lizogyb Igor
 * @since 1.0
 *
 */
class FrameworkException extends \Exception 
{
    const LOG_PATH = 'logs';
    public $statusCode;
        
    public function __construct($message = "", $code = 200, $enableLog = true, Exception $previous = NULL)
    {
   		parent::__construct($message, (int) $code, $previous);
        $response = new Response;
        $response->setStatusCode($code);
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->setContent("<h2>Сталась помилка в срверних скриптах!</h2>
            <p><b>Код помилки: [$code]</b><br/><i>$message</i><br/>
            Вибачте за незручності! Вона буде  виправлена в найкротші строки!</p>");
        $response->send();
        $this->statusCode = $code;

        if($enableLog) {
            self::logError($this);
        }
    }
    /**
     * Метод для рядкового відображення  помилки
     * @return string зміст помилки
     */
    public function __toString()
	{ 
		return self::getText($this);
	}
    
    /**
     * Метод для отримання рядка зі змістом помилки
     * @param Exception $e помилка
     * @return string зміст помилки
     */
    public static function getText(\Exception $e)
	{
    return sprintf('{%s} [ %s ]: "%s" %s [ %d ]',
			get_class($e), $e->getCode(), strip_tags($e->getMessage()), $e->getFile(), $e->getLine());
	}
    
    /**
     * Метод для запису змісту помилки в логи сайту
     * @param Exception $e помилка
     * @return string зміст помилки
     */    
    public static function logError(\Exception $e)
	{
        // Запис в логи сайту 
        $filePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . self::LOG_PATH . DIRECTORY_SEPARATOR . date('Y-m-d').'.txt';
        $date = date('Y-m-d H:i:s (T)');

        if(file_exists($filePath)) {
            $file = fopen($filePath, 'a');
        } else {
            $file = fopen($filePath, 'w+');
        }
       
        $err = '#'. $date.': '. self::getText($e) ."\n";
        fwrite($file, $err);
        fclose($file);
    }

}
?>
