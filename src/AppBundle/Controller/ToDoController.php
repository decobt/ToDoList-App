<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class ToDoController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function toDoAction(){
        return new Response("hello");
    }
    /**
    * @Route("/edit/{post}")
    */
    public function editPost($post){
        return new Response("edit post page");
    }
}

?>