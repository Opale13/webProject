<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index()
    {
        $categories = $this->getDoctrine()
                           ->getRepository(Category::class)
                           ->findAll();

        return $this->render('category/displayCategory.html.twig', array("categories" => $categories));
    }

    /**
     * @Route("/createCategory", name="createCategory")
     */
    public function createCategory(Request $request)
    {
        $cat = new Category();
        $form = $this->createForm(CategoryType::class, $cat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();

            return $this->redirect($this->generateUrl('category'));
        }

        return $this->render('category/createCategory.html.twig', array('form' => $form->createView()));
    }

    /**
    * @Route("/modifyCategory/{id}", name="modifyCategory")
    */
    public function modifyCategory(Request $request, $id)
    {
        $category = $this->getDoctrine()
                     ->getRepository(category::class)
                     ->find($id);

         $form = $this->createForm(categoryType::class, $category);
         $form->handleRequest($request);

         if ($form->isSubmitted() && $form->isValid())
         {
             $em = $this->getDoctrine()->getManager();
             $em->persist($category);
             $em->flush();

             return $this->redirect($this->generateUrl('category'));
         }

        return $this->render('task/modifyCategory.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/deleteCategory/{id}", name="deleteCategory")
     */
    public function deleteCategory($id)
    {
        $category = $this->getDoctrine()
                      ->getRepository(category::class)
                      ->findAll();

        if ($id != null) {
            $em = $this->getdoctrine()->getManager();
            $category = $em->getRepository(category::class)->find($id);
            $em->remove($category);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('category'));
    }
}
