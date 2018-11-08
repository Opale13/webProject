<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @Route("/task", name="task")
     */
    public function index()
    {
        try {
            $tasks = $this->getDoctrine()
                      ->getRepository(Task::class)
                      ->findAll();
        }
        catch (Exception $e) {}      

        return $this->render('task/displayTask.html.twig', array("tasks" => $tasks));
    }

    /**
     * @Route("/createTask", name="createTask")
     */
    public function createTask(Request $request)
    {
        try {            
            $task = new Task();
            $form = $this->createForm(TaskType::class, $task);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($task);
                    $em->flush();

                    $this->addFlash('notice', 'Create with success');                    
                }
                catch (Exception $e) {
                    $this->addFlash('notice', "Doesn't create with success");
                }

                return $this->redirect($this->generateUrl('task'));                
            }
        }
        catch (Exception $e) {
            $this->addFlash('notice', "Error");
            return $this->redirect($this->generateUrl('task'));   
        }

        return $this->render('task/createTask.html.twig', array('form' => $form->createView()));
    }

    /**
    * @Route("/modifyTask/{id}", name="modifyTask")
    */
    public function modifyTask(Request $request, $id)
    {
        try {
            $task = $this->getDoctrine()
                     ->getRepository(Task::class)
                     ->find($id);      

            $form = $this->createForm(TaskType::class, $task);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($task);
                    $em->flush();

                    $this->addFlash('notice', "Modify with success");
                }
                catch (Exception $e) {
                    $this->addFlash('notice', "Doesn't modify with success");
                }  
                     
                return $this->redirect($this->generateUrl('task'));         
            }
        }
        catch (Exception $e) {
            $this->addFlash('notice', "Error");
            return $this->redirect($this->generateUrl('task'));
        }

        return $this->render('task/modifyTask.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/deleteTask/{id}", name="deleteTask")
     */
    public function deleteTask($id=null)
    {
        try {
            $tasks = $this->getDoctrine()
                      ->getRepository(Task::class)
                      ->findAll();

            if ($id != null) {
                try {
                    $em = $this->getdoctrine()->getManager();
                    $task = $em->getRepository(Task::class)->find($id);
                    $em->remove($task);
                    $em->flush();

                    $this->addFlash('notice', "Delete with success");
                }
                catch (Exception $e) {
                    $this->addFlash('notice', "Doesn't delete with success");
                }
                
            }
        }
        catch (Exception $e) {
            $this->addFlash('notice', "Error");
        }        

        return $this->redirect($this->generateUrl('task'));
    }
}
