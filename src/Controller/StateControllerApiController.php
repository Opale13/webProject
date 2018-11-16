<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;use App\Entity\Task;
use App\Entity\State;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class StateControllerApiController extends AbstractController
{
    /**
     * @Route("/api/states", name="api_state", methods={"GET"})
     */
    public function getStates()
    {
        $response = new Response();
        $query = array();
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $states = $this->getDoctrine()
                      ->getRepository(State::class)
                      ->findAll();

        $jsonContent = $serializer->serialize($states, 'json');

        $response->setContent($jsonContent);
        $response->headers->set('Content-Type', 'application/json');
        $response->setStatusCode('200');

        return $response;
    }
}
