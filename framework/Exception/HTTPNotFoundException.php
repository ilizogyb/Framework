<?php
/**
 * HTTPNotFoundException викидається коли виникає помилка пов'язана з 
 * існуванням запрошеної інформації(ресурсу)
 *
 * @author     Lizogyb Igor
 * @version    1.0
 */

namespace Framework\Exception;

use \Framework\Renderer\Renderer;
use \Framework\Response\Response;

class HTTPNotFoundException extends FrameworkException
{
    public function __construct($message, $code='404')
    {
        //disable display errors
        ini_set('display_errors','Off');
        $renderer = new Renderer('errorLayout.html', array('message'=>$message, 'code'=>$code, 'image'=>'notfound404.png'));
        $response = new Response($renderer->render());
        $response->send();
    }
}
?>
