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
        try {
            $categories = $this->getDoctrine()
                               ->getRepository(Category::class)
                               ->findAll();
        }
        catch (Exception $e) {}

        return $this->render('category/displayCategory.html.twig', array("categories" => $categories));
    }

    /**
     * @Route("/createCategory", name="createCategory")
     */
    public function createCategory(Request $request)
    {
        try {        
            $cat = new Category();
            $form = $this->createForm(CategoryType::class, $cat);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($cat);
                    $em->flush();

                    $this->addFlash('notice', 'Create with success');
                }
                catch (Exception $e) {
                    $this->addFlash('notice', "Doesn't create with success");
                }

                return $this->redirect($this->generateUrl('category'));
            }
        }
        catch (Exception $e){
            $this->addFlash('notice', "Error");
            return $this->redirect($this->generateUrl('category'));
        }

        return $this->render('category/createCategory.html.twig', array('form' => $form->createView()));
    }

    /**
    * @Route("/modifyCategory/{id}", name="modifyCategory")
    */
    public function modifyCategory(Request $request, $id)
    {
        try {
            $category = $this->getDoctrine()
                     ->getRepository(category::class)
                     ->find($id);     

            $form = $this->createForm(categoryType::class, $category);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                try {
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($category);
                    $em->flush();

                    $this->addFlash('notice', "Modify with success");
                }
                catch (Exception $e) {
                    $this->addFlash('notice', "Doesn't modify with success");
                }

                return $this->redirect($this->generateUrl('category'));
            }
        }
        catch (Exception $e) {
            $this->addFlash('notice', "Error");
            return $this->redirect($this->generateUrl('category'));
        }

        return $this->render('category/modifyCategory.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/deleteCategory/{id}", name="deleteCategory")
     */
    public function deleteCategory($id)
    {
        try {        
            if ($id != null) {
                try {
                    $em = $this->getdoctrine()->getManager();
                    $category = $em->getRepository(category::class)->find($id);
                    $em->remove($category);
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

        return $this->redirect($this->generateUrl('category'));
    }
}
