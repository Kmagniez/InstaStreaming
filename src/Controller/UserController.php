<?php

namespace App\Controller;

use App\Entity\Movie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController

{
    /**
     * @Route("/user", name="user") 
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/movie/add/favourite/{id}", name="add_favourite_movie", options={"expose"=true})
     */
    public function addFavoriteMovie(Movie $movie): Response{

        /**
         * @var Admin $user
         */
        $user=$this->getUser();
        if (!$user->getMovies()->contains($movie)){
            $user->addMovie($movie);
            $message='added';
        }else{
            $user->removeMovie($movie);
            $message='removed';
        }
        
        $em=$this->getDoctrine()->getManager();
        $em->persist($user);
        $em->persist($movie);
        $em->flush();


        return new JsonResponse($message);
    }
}


