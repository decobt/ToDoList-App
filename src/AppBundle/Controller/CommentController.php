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

class CommentController extends Controller
{
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