<?php
/**
 * Routexception викидається коли виникає помилка 
 * коли роут не знеайдений
 * 
 *
 * @author     Lizogyb Igor
 * @version    1.0
 */
 
namespace Framework\Exception;

use \Framework\Renderer\Renderer;
use \Framework\Response\Response;

class RouteException extends FrameworkException
{
    public function __construct($message, $code)
    {
        //disable display errors
        ini_set('display_errors','Off');
        $renderer = new Renderer('errorLayout.html', array('message'=>$message, 'code'=>$code));
        $response = new Response($renderer->render());
        $response->send();
    }
}
