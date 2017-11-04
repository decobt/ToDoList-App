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
use Symfony\Component\HttpFoundation\Request;

class ToDoController extends Controller
{
    /**
    * @Route("/", name="homepage")
    */
    public function toDoAction(Request $request){
        
        // create a task and give it some dummy data
        $task = new ToDoItem();
        $task->setDate(new \DateTime('tomorrow'));
        
        // create the form
        $form = $this->createFormBuilder($task)
        ->add('description', TextareaType::class)
        ->add('date', DateType::class, array('widget' => 'single_text'))
        ->add('save', SubmitType::class, array('label' => 'Create Task'))
        ->getForm();
        
        $repository = $this->getDoctrine()->getRepository('AppBundle:ToDoItem');
        $alltasks = $repository->findBy([], ['date' => 'ASC']);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //saving the item to the database
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            //Add flash message to session
            $this->addFlash(
                'success',
                'Your task was saved!'
            );
            
            //render the view
            return $this->render('base.html.twig', array(
                'title'=>'HomePage',
                'message'=>'ToDo List App',
                'form'=>$form->createView(),
                'tasks'=>$alltasks
            ));
        }
        //render the view
        return $this->render('base.html.twig', array(
            'title'=>'HomePage',
            'message'=>'ToDo List App',
            'form'=>$form->createView(),
            'tasks'=>$alltasks
        ));
    }
    
    /**
    * @Route("/edit/{post}")
    */
    public function editPost(Request $request, $post){
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:ToDoItem')->find($post);
        
        $form = $this->createFormBuilder($product)
        ->add('description', TextareaType::class)
        ->add('date', DateType::class, array('widget' => 'single_text'))
        ->add('save', SubmitType::class, array('label' => 'Update Task'))
        ->getForm();
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //saving the item to the database
            $em = $this->getDoctrine()->getManager();
            $task = $form->getData();
            $em->persist($task);
            $em->flush();
            
            //Add flash message to session
            $this->addFlash(
                'success',
                'Task was succesfully updated'
            );
            return $this->redirectToRoute('homepage');
        }
        
        return $this->render('edit.html.twig', array(
            'title'=>'Edit Task',
            'message'=>'ToDo List App',
            'form'=>$form->createView()
        ));
    }
    
    /**
    * @Route("/remove/{post}")
    */
    public function removePost($post){
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:ToDoItem')->find($post);
        
        if(!$product){
            $this->addFlash(
                'warning',
                'This task does not exist and can\'t be removed!'
            );
            return $this->redirectToRoute('homepage');
        }
        $em->remove($product);
        $em->flush();
        
        $this->addFlash(
                'success',
                'Your task was succesfully removed!'
            );
        
        return $this->redirectToRoute('homepage');
    }
}

?>