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
        $response = new Response();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $categories = $this->getDoctrine()
                           ->getRepository(Category::class)
                           ->findAll();

        $jsonContent = $serializer->serialize($categories, 'json');

        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode('200');

        return $response;
    }

    /**
     * @Route("/api/category/{id}", name="api_category", methods={"GET"})
     */
    public function getCategory($id)
    {
        $response = new Response();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        if ($id!= null) {
            $category = $this->getDoctrine()
                           ->getRepository(Category::class)
                           ->find($id);
            
            if ($category != null) {
                $jsonContent = $serializer->serialize($category, 'json');
    
                $response->setContent($jsonContent);
                $response->headers->set('Content-Type', 'application/json');
                $response->setStatusCode('200');
            }
            else {
                $response->setStatusCode('404');
            }

        }        
        else {
            $response->setStatusCode('404');
        }

        return $response;
    }

    /**
     * @Route("/api/createCategory", name="api_createCategory", methods={"POST", "OPTIONS"})
     */
    public function createCategory(Request $request)
    {
        $response = new Response();
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);

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
            
            $query['valid'] = true; 
            $query['data'] = array('title' => $content["title"],
                                   'description' => $content["description"]);
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
    * @Route("/api/modifyCategory/{id}", name="api_modifyCategory", methods={"PUT", "OPTIONS"})
    */
    public function modifyCategory(Request $request, $id)
    {
        $response = new Response();
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);

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

            $query['valid'] = true; 
            $query['data'] = array('id' => $id,
                                   'title' => $content["title"],
                                   'description' => $content["description"]);
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
     * @Route("/api/deleteCategory/{id}", name="api_deleteCategory", methods={"DELETE", "OPTIONS"})
     */
    public function deleteCategory($id)
    {
        $response = new Response();
        $query = array();

        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS')
        {
            $response->headers->set('Content-Type', 'application/text');
            $response->headers->set('Access-Control-Allow-Origin', '*');
            $response->headers->set('Access-Control-Allow-Methods', 'GET, PUT, POST, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type',true);

            return $response;
        }

        if ($id != null) {
            $em = $this->getdoctrine()->getManager();
            $category = $em->getRepository(category::class)->find($id);
            $em->remove($category);
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
