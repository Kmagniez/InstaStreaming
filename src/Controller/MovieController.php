<?php

namespace App\Controller;

use App\Entity\GenreMovie;
use App\Entity\Movie;
use App\Form\MovieType;
use App\Repository\GenreMovieRepository;
use App\Repository\MovieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/movie")
 * @IsGranted("ROLE_USER")
 */
class MovieController extends AbstractController
{


    /**
     * @Route("/genre/{Name}", name="movie_index_genre", methods={"GET"})
     */
    public function indexByGenre(GenreMovieRepository $genremovieRepository, GenreMovie $genre): Response
     {
         return $this->render('movie/index.html.twig', [
            'movies'=>$genre->getMovies(),
            'genre'=>$genre,
            'genres'=> $genremovieRepository->findAll()
         ]);

    }



    /**
     * @Route("/", name="movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository, GenreMovieRepository $genreMovieRepository,$page, PaginatorInterface $paginator): Response
    {
        $query=$movieRepository->createQueryBuilder('m')->getQuery;
        $pagination=$paginator->paginate($query, $page, 5);

        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
            'genres' => $genreMovieRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="movie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_show", methods={"GET"})
     */
    public function show(Movie $movie): Response
    {
        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="movie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Movie $movie): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Movie $movie): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($movie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('movie_index');
    }

}
