<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GameRepository")
 */
class Game
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
    private $releaseDate;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $publishingHouse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Critic", mappedBy="game")
     */
    private $critics;

    public function __construct()
    {
        $this->critics = new ArrayCollection();
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

    public function getReleaseDate(): ?string
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(string $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishingHouse(): ?string
    {
        return $this->publishingHouse;
    }

    public function setPublishingHouse(string $publishingHouse): self
    {
        $this->publishingHouse = $publishingHouse;

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection|Critic[]
     */
    public function getCritics(): Collection
    {
        return $this->critics;
    }

    public function addCritic(Critic $critic): self
    {
        if (!$this->critics->contains($critic)) {
            $this->critics[] = $critic;
            $critic->setGame($this);
        }

        return $this;
    }

    public function removeCritic(Critic $critic): self
    {
        if ($this->critics->contains($critic)) {
            $this->critics->removeElement($critic);
            // set the owning side to null (unless already changed)
            if ($critic->getGame() === $this) {
                $critic->setGame(null);
            }
        }

        return $this;
    }
}
