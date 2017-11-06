<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ToDoItem;
use AppBundle\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
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
        
        // create a task and give it some data
        $task = new ToDoItem();
        $task->setDate(new \DateTime('tomorrow'));
        
        // build the form to add new tasks
        $form = $this->createFormBuilder($task)
        ->add('description', TextareaType::class)
        ->add('date', DateType::class, array('widget' => 'single_text'))
        ->add('save', SubmitType::class, array('label' => 'Create Task'))
        ->getForm();
        
        // get all tasks sorted by ASC from the DB
        $repository = $this->getDoctrine()->getRepository('AppBundle:ToDoItem');
        $alltasks = $repository->findBy([], ['date' => 'ASC']);
        
        // handle the request and check if the form is submitted
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
            
            //redirect to homepage
            return $this->redirectToRoute('homepage');
        }
        
        //render the view
        return $this->render('home.html.twig', array(
            'title'=>'HomePage',
            'message'=>'ToDo List App',
            'form'=>$form->createView(),
            'tasks'=>$alltasks
        ));
    }
    
    /**
    * @Route("/edit/{post}")
    */
    public function editPostAction(Request $request, $post){
        // find the task based on post number (id)
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:ToDoItem')->find($post);
         
        // build the update form based on the data from the DB    
        $form = $this->createFormBuilder($product)
        ->add('description', TextareaType::class)
        ->add('date', DateType::class, array('widget' => 'single_text'))
        ->add('save', SubmitType::class, array('label' => 'Update Task'))
        ->getForm();
        
        //create comment object
        $comment = new Comments();
        
        // build the comment form
        $comment_form = $this->createFormBuilder($comment)
        ->setAction('/add_comment')
        ->add('comment', TextareaType::class)
        ->add('todo_id', HiddenType::class, array('data' => $post))
        ->add('save', SubmitType::class, array('label' => 'Add Comment'))
        ->getForm();
        
        // handle the form submission and update the task in the DB
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            // update the task in the database
            $em = $this->getDoctrine()->getManager();
            $task = $form->getData();
            $em->persist($task);
            $em->flush();
            
            // add flash message to session
            $this->addFlash(
                'success',
                'Task was succesfully updated'
            );
            
            // redirect to homepage
            return $this->redirectToRoute('homepage');
        }
        
        // find all comments for the task, later pass it to the view
        $repository = $this->getDoctrine()->getRepository('AppBundle:Comments');
        $getComments = $repository->findBy(array('todo_id'=>$post));
        
        // render the view
        return $this->render('edit.html.twig', array(
            'title'=>'Edit Task',
            'message'=>'ToDo List App',
            'form'=>$form->createView(),
            'comment_form'=>$comment_form->createView(),
            'comments'=>$getComments
        ));
    }
    
    /**
    * @Route("/remove/{post}")
    */
    public function removePostAction($post){
        
        // get the task from the DB based on $post
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('AppBundle:ToDoItem')->find($post);
        
        // if the task does not exist in DB
        if(!$product){
            // add message to session
            $this->addFlash(
                'warning',
                'This task does not exist and can\'t be removed!'
            );
            
            // redirect to homepage
            return $this->redirectToRoute('homepage');
        }
        
        // if tasks exist, check if any comments are assosiated with it
        $comments = $em->getRepository('AppBundle:Comments')->findBy(array('todo_id'=>$post));
        
        // if comments exist, remove them first
        if($comments){
            foreach ($comments as $op) {
                $em->remove($op);
            } 
        }
        
        // finally remove the task
        $em->remove($product);
        $em->flush();
        
        // add a message to the session
        $this->addFlash(
                'success',
                'Your task was succesfully removed!'
            );
        
        // redirect to homepage
        return $this->redirectToRoute('homepage');
    }
    
    /**
    * @Route("/add_comment")
    * @Method("POST")
    */
    public function addCommentAction(Request $request){
        // get the form data
        $data = $request->request->all();
        $form = $data['form'];
        
        $em = $this->getDoctrine()->getManager();
        
        // create a new Comments Object
        $comment = new Comments();
        $comment -> setComment($form['comment']);
        $comment -> setTodoId($em->getRepository('AppBundle:ToDoItem')->find($form['todo_id']));
        
        // add the data to the DB
        $em -> persist($comment);
        $em ->flush();
        
        // add a message to the session
        $this->addFlash(
                'success',
                'Your comment was succesfully added!'
            );
        
        // redirect to route
        return $this->redirectToRoute('app_todo_editpost', array('post' => $form['todo_id']));
    }
    
    /**
    * @Route("/remove/{post}/{comment}", name="removeComment")
    * @Method("GET")
    */
    public function removeCommentAction($post, $comment){
        $em = $this->getDoctrine()->getManager();
        
        // search for comment in DB based on id
        $item = $em->getRepository('AppBundle:Comments')->find($comment);
        
        // if it does not exist, add a message to session
        if(!$item){
            $this->addFlash(
                'warning',
                'This comment does not exist and can\'t be removed!'
            );
            // redirect to route
            return $this->redirectToRoute('app_todo_editpost',array('post' => $post));
        }
        
        // if it exists, remove it from DB
        $em->remove($item);
        $em->flush();
        
        // add a flash message to session
        $this->addFlash(
                'success',
                'Your comment was succesfully removed!'
            );
        
        // redirect to route
        return $this->redirectToRoute('app_todo_editpost',array('post' => $post));
    }
}

?>