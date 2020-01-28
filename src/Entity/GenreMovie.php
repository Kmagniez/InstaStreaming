<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GenreMovieRepository")
 */
class GenreMovie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Movie", mappedBy="Genres")
     */
    private $Movies;

    /**
     * @ORM\Column(type="integer")
     */
    private $imdbID;

    public function __construct()
    {
        $this->Movies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    /**
     * @return Collection|Movie[]
     */
    public function getMovies(): Collection
    {
        return $this->Movies;
    }

    public function addMovie(Movie $movie): self
    {
        if (!$this->Movies->contains($movie)) {
            $this->Movies[] = $movie;
            $movie->addGenre($this);
        }

        return $this;
    }

    public function removeMovie(Movie $movie): self
    {
        if ($this->Movies->contains($movie)) {
            $this->Movies->removeElement($movie);
            $movie->removeGenre($this);
        }

        return $this;
    }

    public function getImdbID(): ?int
    {
        return $this->imdbID;
    }

    public function setImdbID(int $imdbID): self
    {
        $this->imdbID = $imdbID;

        return $this;
    }
}
