<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class ToDoController extends Controller
{
    /**
    * @Route("/", name="homepage")
    * @Method("GET")
    */
    public function toDoAction(){
        return $this->render('base.html.twig', array(
            'title'=>'HomePage',
            'message'=>'Welcome to HomePage'
        ));
    }
    /**
    * @Route("/edit/{post}")
    */
    public function editPost($post){
        return new Response("edit post page");
    }
}

?>