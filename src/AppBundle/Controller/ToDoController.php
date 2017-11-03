<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ToDoItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

class ToDoController extends Controller
{
    /**
    * @Route("/", name="homepage")
    * @Method("GET")
    */
    public function toDoAction(){
        
        // create a task and give it some dummy data for this example
        $task = new ToDoItem();
        $task->setDescription('Write a blog post');
        $task->setDate(new \DateTime('tomorrow'));
        
        $form = $this->createFormBuilder($task)
        ->add('description', TextareaType::class)
        ->add('date', DateType::class, array('widget' => 'single_text'))
        ->add('save', SubmitType::class, array('label' => 'Create Task'))
        ->getForm();
        
        return $this->render('base.html.twig', array(
            'title'=>'HomePage',
            'message'=>'Welcome to HomePage',
            'form'=>$form->createView()
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