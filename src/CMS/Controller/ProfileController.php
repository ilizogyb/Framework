<?php
/**
 * Created by PhpStorm.
 * User: dgilan
 * Date: 10/15/14
 * Time: 12:49 PM
 */

namespace CMS\Controller;

use Framework\Controller\Controller;

class ProfileController extends Controller
{
    public function getAction()
    {
        return $this->render('profile.html', array("table_capt"=>"Your Profile Information"));
    }  
    
    public function updateAction()
    {

    }    
}