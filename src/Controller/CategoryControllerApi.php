<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CategoryControllerApi extends AbstractController
{
    /**
     * @Route("/api/categories", name="api_categories", methods={"GET"})
     */
    public function index()
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $categories = $this->getDoctrine()
                           ->getRepository(Category::class)
                           ->findAll();

        $jsonContent = $serializer->serialize($categories, 'json');

        $response = new JsonResponse();
        $response->setContent($jsonContent);
        $response->setStatusCode('302');

        return $response;
    }

    /**
     * @Route("/api/createCategory", name="api_createCategory", methods={"POST", "OPTIONS"})
     */
    public function createCategory(Request $request)
    {
        $response = new Response();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');

            return $response;
        }

        $json = $request->getContent();
        $content = json_decode($json, true);

        if (isset($content["title"]) && isset($content["description"]))
        {
            $cat = new Category();

            $cat->setTitle($content["title"]);
            $cat->setDescription($content["description"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($cat);
            $em->flush();
            
            $response->setStatusCode('201');
        }
        else 
        {
            $response->setStatusCode('204');
        }        

        return $response;
    }

     /**
    * @Route("/api/modifyCategory/{id}", name="api_modifyCategory", methods={"PUT", "OPTIONS"})
    */
    public function modifyCategory(Request $request, $id)
    {
        $response = new Response();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');

            return $response;
        }

        $json = $request->getContent();
        $content = json_decode($json, true);

        if ($id!= null && isset($content["title"]) && isset($content["description"]))
        {
            $category = $this->getDoctrine()
                     ->getRepository(category::class)
                     ->find($id);

            $category->setTitle($content["title"]);
            $category->setDescription($content["description"]);

            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
            
            $response->setStatusCode('200');
        }
        else 
        {
            $response->setStatusCode('304');
        }        

        return $response;
    }

    /**
     * @Route("/api/deleteCategory/{id}", name="api_deleteCategory", methods={"DELETE", "OPTIONS"})
     */
    public function deleteCategory($id)
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
            $category = $em->getRepository(category::class)->find($id);
            $em->remove($category);
            $em->flush();

            $response->setStatusCode('200');
        }
        else
        {
            $response->setStatusCode('404');
        }

        return $response;
    }
}