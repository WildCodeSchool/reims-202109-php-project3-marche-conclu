<?php

namespace App\Entity;

use App\Repository\SpaceDisponibilityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SpaceDisponibilityRepository::class)
 */
class SpaceDisponibility
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", nullable="true", length=100)
     */
    private ?string $monday;

    /**
     * @ORM\OneToOne(targetEntity=Space::class, inversedBy="spaceDisponibility", cascade={"persist", "remove"})
     */
    private ?Space $space;

    /**
     * @ORM\Column(type="string", nullable="true", length=100)
     */
    private ?string $tuesday;

    /**
     * @ORM\Column(type="string", nullable="true", length=100)
     */
    private ?string $wednesday;

    /**
     * @ORM\Column(type="string", nullable="true", length=100)
     */
    private ?string $thursday;

    /**
     * @ORM\Column(type="string", nullable="true", length=100)
     */
    private ?string $friday;

    /**
     * @ORM\Column(type="string", nullable="true", length=100)
     */
    private ?string $saturday;

    /**
     * @ORM\Column(type="string", nullable="true", length=100)
     */
    private ?string $sunday;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getMonday(): ?string
    {
        return $this->monday;
    }

    public function setMonday(?string $monday): self
    {
        $this->monday = $monday;

        return $this;
    }

    public function getSpace(): ?Space
    {
        return $this->space;
    }

    public function setSpace(?Space $space): self
    {
        $this->space = $space;

        return $this;
    }

    public function getTuesday(): ?string
    {
        return $this->tuesday;
    }

    public function setTuesday(?string $tuesday): self
    {
        $this->tuesday = $tuesday;

        return $this;
    }

    public function getWednesday(): ?string
    {
        return $this->wednesday;
    }

    public function setWednesday(?string $wednesday): self
    {
        $this->wednesday = $wednesday;

        return $this;
    }

    public function getThursday(): ?string
    {
        return $this->thursday;
    }

    public function setThursday(?string $thursday): self
    {
        $this->thursday = $thursday;

        return $this;
    }

    public function getFriday(): ?string
    {
        return $this->friday;
    }

    public function setFriday(?string $friday): self
    {
        $this->friday = $friday;

        return $this;
    }

    public function getSaturday(): ?string
    {
        return $this->saturday;
    }

    public function setSaturday(?string $saturday): self
    {
        $this->saturday = $saturday;

        return $this;
    }

    public function getSunday(): ?string
    {
        return $this->sunday;
    }

    public function setSunday(?string $sunday): self
    {
        $this->sunday = $sunday;

        return $this;
    }
}
