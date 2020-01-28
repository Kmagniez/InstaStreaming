<?php

namespace App\Command;

use App\Entity\Movie;
use App\Entity\GenreMovie;
use App\Entity\Serie;
use App\Repository\MovieRepository;
use App\Repository\GenreMovieRepository;
use App\Repository\SerieRepository;
use Doctrine\Bundle\DoctrineBundle\Mapping\ContainerEntityListenerResolver;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;;

class ImportDataCommand extends Command
{

    /**
     * @var MovieRepository
     */
    private $movieRepo;

    /**
     * @var GenreMovieRepository
     */
    private $genremovieRepo;

    /**
     * @var ContainerInterface
     */
    private $container;

    const API_URL = "https://api.themoviedb.org/3/trending/all/day?language=fr&api_key=5a50d1cd0763fe5043bb578973782631";


    public function __construct(MovieRepository $movieRepository, GenreMovieRepository $genremovieRepository, ContainerInterface $container)
    {

        $this->movieRepo = $movieRepository;
        $this->genremovieRepo = $genremovieRepository;
        $this->container = $container;
        $this->em = $this->container->get('doctrine')->getManager();

        parent::__construct();
    }

    protected static $defaultName = 'app:import-data';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $httpClient = HttpClient::create();
        $responseContent = json_decode($httpClient->request('GET', self::API_URL)->getContent());

        foreach ($responseContent->results as $r) {

            dump($r);
            $this->ImportMovie($r, $io, $this->em);
        }

        $this->em->flush();

        return 0;
    }




    function ImportMovie($r, $io, $em)
    {

        $nbMoviesCreated = 0;


        if ($r->media_type == "movie") {

            $movie = $this->movieRepo->findOneBy(['imdbID' => $r->id]);

            // Si on ne trouve pas le film par son identifiant IMDB
            if (!$movie) {

                // Création d'un film
                $movie = new Movie();
                $movie->setTitle($r->title);
                $movie->setImage($r->poster_path);
                $movie->setReleaseDate(new \DateTime($r->release_date));
                $movie->setNote($r->vote_average);
                $movie->setImdbID($r->id);
                $movie->setOverview($r->overview);
                $em->persist($movie);


                // Incrémentation du compteur
                $nbMoviesCreated++;
            }

            if (count($r->genre_ids) > 0 && count($movie->getGenres()) == 0) {
                foreach ($r->genre_ids as $genreId) {

                    $genre = $this->genremovieRepo
                        ->findOneBy(['imdbID' => $genreId]);
                    $movie->addGenre($genre);
                }
            }
        }

        $io->success($nbMoviesCreated . ' films ont été créés :)');
    }
}