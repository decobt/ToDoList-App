<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends Controller
{
    /**
     * @Route("/api/getToDo", name="api_get_todo")
     */
    public function getToDo(Request $request)
    {
      // get all tasks sorted by ASC from the DB
      $repository = $this->getDoctrine()->getRepository('AppBundle:ToDoItem');
      $alltasks = $repository->findBy([], ['date' => 'ASC']);

      $row = array();
      foreach($alltasks as $task){
        array_push($row, array(
          'id'=>$task->getId(),
          'description'=>$task->getDescription(),
          'date'=>$task->getDate()
          )
        );
      }
      // convert array to json response
      $response = new JsonResponse($row);

      $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');
      //return the json response
      return $response;
    }

    /**
     * @Route("/api/addToDo", name="api_add_todo")
     */
    public function addToDo(Request $request)
    {
        // replace this example code with whatever you need
        return new Response('addToDo');
    }

    /**
     * @Route("/api/getComments/{id}/", name="api_get_comments")
     */
    public function getComments(Request $request, $id)
    {
        $task_id = $id;

        // find all comments for the task, later pass it to the view
        $repository = $this->getDoctrine()->getRepository('AppBundle:Comments');
        $getComments = $repository->findBy(array('todo_id'=>$task_id));

        $row = array();
        foreach($getComments as $comment){
          array_push($row, array(
            'id'=>$comment->getId(),
            'comment'=>$comment->getComment()
          ));
        }
        // convert array to json response
        $response = new JsonResponse($row);
        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:3000');
        //return the json response
        return $response;
    }

    /**
     * @Route("/api/addComments", name="api_add_comments")
     */
    public function addComments(Request $request)
    {
        // replace this example code with whatever you need
        return new Response('addComments');
    }
}
