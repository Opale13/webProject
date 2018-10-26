<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Entity\Category;
use App\Entity\State;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TaskControllerApi extends AbstractController
{
    /**
     * @Route("/api/tasks", name="api_task", methods={"GET"})
     */
    public function index()
    {
        $response = new Response();

        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $tasks = $this->getDoctrine()
                      ->getRepository(Task::class)
                      ->findAll();

        $jsonContent = $serializer->serialize($tasks, 'json');

        $response = new JsonResponse();
        $response->setContent($jsonContent);
        $response->setStatusCode('302');

        return $response;
    }

    /**
     * @Route("/api/createTask", name="api_createTask", methods={"POST", "OPTIONS"})
     */
    public function createTask(Request $request)
    {
        $response = new Response();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');

            return $response;
        }

        $json = $request->getContent();
        $content = json_decode($json, true);

        if (isset($content["title"]) && isset($content["description"]) && isset($content["fkCategory"]) && isset($content["fkState"]))
        {
            $task = new Task();
            $category = $this->getDoctrine()
                             ->getRepository(Category::class)
                             ->find($content["fkCategory"]);
            $state = $this->getDoctrine()
                             ->getRepository(State::class)
                             ->find($content["fkState"]);

            $task->setTitle($content["title"]);
            $task->setDescription($content["description"]);
            $task->setFkCategory($category);
            $task->setFkState($state);

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();            
            
            $query['valid'] = true; 
            $query['data'] = array('title' => $content["title"],
                                   'description' => $content["description"],
                                   'category' => json_decode($serializer->serialize($category, 'json')),
                                   'state' => json_decode($serializer->serialize($state, 'json')));
            $response->setStatusCode('201');
        }
        else 
        {
            $query['valid'] = false; 
            $query['data'] = null;
            $response->setStatusCode('404');
        }        

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($query));
        return $response;
    }

     /**
    * @Route("/api/modifyTask/{id}", name="api_modifyTask", methods={"PUT", "OPTIONS"})
    */
    public function modifyTask(Request $request, $id)
    {
        $response = new Response();        
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');

            return $response;
        }

        $json = $request->getContent();
        $content = json_decode($json, true);

        if ($id!= null && isset($content["title"]) && isset($content["description"]) && isset($content["fkCategory"]) && isset($content["fkState"]))
        {
            $task = $this->getDoctrine()
                         ->getRepository(Task::class)
                         ->find($id);
            
            $category = $this->getDoctrine()
                             ->getRepository(Category::class)
                             ->find($content["fkCategory"]);
            $state = $this->getDoctrine()
                          ->getRepository(State::class)
                          ->find($content["fkState"]);
            
            $task->setTitle($content["title"]);
            $task->setDescription($content["description"]);
            $task->setFkCategory($category);
            $task->setFkState($state);

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();
            
            $query['valid'] = true; 
            $query['data'] = array('title' => $content["title"],
                                   'description' => $content["description"],
                                   'category' => json_decode($serializer->serialize($category, 'json')),
                                   'state' => json_decode($serializer->serialize($state, 'json')));
            $response->setStatusCode('200');
        }
        else 
        {
            $query['valid'] = false; 
            $query['data'] = null;
            $response->setStatusCode('404');
        }        

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($query));
        return $response;
    }

    /**
     * @Route("/api/deleteTask/{id}", name="api_deleteTask", methods={"DELETE", "OPTIONS"})
     */
    public function deleteTask($id=null)
    {
        $response = new Response();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');

            return $response;
        }

        if ($id != null) {
            $em = $this->getdoctrine()->getManager();
            $task = $em->getRepository(Task::class)->find($id);
            $em->remove($task);
            $em->flush();

            $query['valid'] = true;
            $response->setStatusCode('200');
        }
        else
        {
            $query['valid'] = false;
            $response->setStatusCode('404');
        }

        $response->headers->set('Content-Type', 'application/json');
        $response->setContent(json_encode($query));
        return $response;
    }

}
