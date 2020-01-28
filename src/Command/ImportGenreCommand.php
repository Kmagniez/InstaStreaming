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

class ImportGenreCommand extends Command
{

    /**
	 * @var GenreMovieRepository
	 */
    private $genremovieRepo;

	/**
	 * @var ContainerInterface
	 */
	private $container;


    CONST API_URL="https://api.themoviedb.org/3/genre/movie/list?api_key=5a50d1cd0763fe5043bb578973782631&language=fr-FR";


    public function __construct(GenreMovieRepository $genremovieRepository, ContainerInterface $container){


        $this->genremovieRepo=$genremovieRepository;
        $this->container=$container;

        parent::__construct();

    }

    protected static $defaultName = 'app:import-genre';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
        ;
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $httpClient = HttpClient::create();
        $responseContent = json_decode($httpClient->request('GET', self::API_URL)->getContent());

		/**
		 * @var EntityManager $em
		 */
        $em = $this->container->get('doctrine')->getManager();



        foreach ($responseContent->genres as $r){
            
            dump($r);

            $this->ImportGenre($r, $io, $em);
        }

        $em->flush();        


        return 0;


    }

    Function ImportGenre($r, $io, $em){

        $nbGenreMoviesCreated = 0;

            // Si on ne trouve pas le genre par son identifiant
            if (!$this->genremovieRepo->findOneBy(['imdbID' => $r->id])){

                // Création d'un film
                $genremovie = new GenreMovie();
                $genremovie->setName($r->name);
                $genremovie->setImdbID($r->id);


                $em->persist($genremovie);


                // Incrémentation du compteur
                $nbGenreMoviesCreated++;                      

            }
            
        $io->success($nbGenreMoviesCreated . ' genres ont été créés :)');


    }

}
