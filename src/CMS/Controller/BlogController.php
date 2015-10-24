<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 10/15/14
 * Time: 12:49 PM
 */

namespace CMS\Controller;

use Framework\Controller\Controller;
use Blog\Model\Post;

class BlogController extends Controller
{
    public function editAction($id)
    { 
       echo "The edit action not implemented yet";
    } 
    
    public function removeAction($id)
    {    
        if ($post = Post::delete((int)$id)) {
            return $this->redirect($this->generateRoute('home'), 'The data has been remove successfully'); 
        } else {
            throw new HttpNotFoundException('Page Not Found!');
        }
    }
}