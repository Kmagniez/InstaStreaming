<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
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
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="datetime")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $note;

    /**
     * @ORM\Column(type="integer")
     */
    private $imdbID;

    /**
     * @ORM\Column(type="text")
     */
    private $overview;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\GenreMovie", inversedBy="Movies")
     */
    private $Genres;

    public function __construct()
    {
        $this->Genres = new ArrayCollection();
    }


    public function __contruct($title, $image, $releaseDate, $note, $imdbID, $overview){

        $this->setTitle($title);
        $this->setImage($image);
        $this->setReleaseDate($releaseDate);
        $this->setNote($note);
        $this->setImdbID($imdbID);
        $this->setOverview($overview);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): self
    {
        $this->note = $note;

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

    public function getOverview(): ?string
    {
        return $this->overview;
    }

    public function setOverview(string $overview): self
    {
        $this->overview = $overview;

        return $this;
    }

    /**
     * @return Collection|GenreMovie[]
     */
    public function getGenres(): Collection
    {
        return $this->Genres;
    }

    public function addGenre(GenreMovie $genre): self
    {
        if (!$this->Genres->contains($genre)) {
            $this->Genres[] = $genre;
        }

        return $this;
    }

    public function removeGenre(GenreMovie $genre): self
    {
        if ($this->Genres->contains($genre)) {
            $this->Genres->removeElement($genre);
        }

        return $this;
    }
}
