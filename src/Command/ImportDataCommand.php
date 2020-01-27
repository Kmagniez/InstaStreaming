<?php

namespace App\Command;


use App\Entity\Movie;
use App\Entity\Serie;
use App\Repository\MovieRepository;
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
	 * @var ContainerInterface
	 */
	private $container;

    CONST API_URL = "https://api.themoviedb.org/3/trending/all/day?language=fr&api_key=5a50d1cd0763fe5043bb578973782631";


    public function __construct(MovieRepository $movieRepository, ContainerInterface $container){

        $this->movieRepo=$movieRepository;
        $this->container=$container;

        parent::__construct();

    }



    protected static $defaultName = 'app:import-data';

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


        $nbMoviesCreated = 0;
        foreach ($responseContent->results as $r){

            dump($r);

        	if ($r->media_type == "movie"){

        		// Si on ne trouve pas le film par son identifiant IMDB
        		if (!$this->movieRepo->findOneBy(['imdbID' => $r->id])){

                    // Création d'un film
                    $movie = new Movie();
                    $movie->setTitle($r->title);
                    $movie->setImage($r->poster_path);
                    $movie->setReleaseDate(New \DateTime($r->release_date));
                    $movie->setNote($r->vote_average);
                    $movie->setImdbID($r->id);
                    $movie->setOverview($r->overview);
                    $em->persist($movie);


                    // Incrémentation du compteur
                    $nbMoviesCreated++;
                        
                    

				}
			}
		}
        $em->flush();

        $io->success($nbMoviesCreated . ' films ont été créés :)');

        return 0;
    }
}
