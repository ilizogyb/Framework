<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 10/15/14
 * Time: 12:49 PM
 */

namespace CMS\Controller;

use Framework\Controller\Controller;
use Framework\Exception\HttpNotFoundException;
use Blog\Model\Post;

class BlogController extends Controller
{
    public function editAction($id)
    {  
        if ($this->getRequest()->isPost()) {
            $templPost = Post::find((int)$id);
            $post = new Post();
            $date = new \DateTime($templPost->date);
            $post->date    = $date->format('Y-m-d H:i:s');
            $post->title = $this->getRequest()->post('title');
            $post->content = $this->getRequest()->post('content');
            $post->id = $templPost->id;
            $post->save();
            return $this->redirect($this->generateRoute('home'), 'The modification of the post is successful'); 
        } else {
            if ($post = Post::find((int)$id)) {
                return $this->render('add.html', array('post' => $post, 'action'=>'/posts/'. $id .'/edit'));  
            } else {
                throw new HttpNotFoundException('Page Not Found!');
            }
        }
    } 
    
    public function removeAction($id)
    {    
        if ($post = Post::delete((int)$id)) {
            return $this->redirect($this->generateRoute('home'), 'The data has been remove successfully'); 
        }
    }
}