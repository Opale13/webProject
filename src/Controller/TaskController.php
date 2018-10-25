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
        $tasks = $this->getDoctrine()
                      ->getRepository(Task::class)
                      ->findAll();

        return $this->render('task/displayTask.html.twig', array("tasks" => $tasks));
    }

    /**
     * @Route("/createTask", name="createTask")
     */
    public function createTask(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('task'));
        }

        return $this->render('task/createTask.html.twig', array('form' => $form->createView()));
    }

    /**
    * @Route("/modifyTask/{id}", name="modifyTask")
    */
    public function modifyTask(Request $request, $id)
    {
        $task = $this->getDoctrine()
                     ->getRepository(Task::class)
                     ->find($id);

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirect($this->generateUrl('task'));
        }

        return $this->render('task/modifyTask.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/deleteTask/{id}", name="deleteTask")
     */
    public function deleteTask($id=null)
    {
        $tasks = $this->getDoctrine()
                      ->getRepository(Task::class)
                      ->findAll();

        if ($id != null) {
            $em = $this->getdoctrine()->getManager();
            $task = $em->getRepository(Task::class)->find($id);
            $em->remove($task);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('task'));
    }
}
