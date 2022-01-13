<?php

namespace App\Entity;

use App\Repository\SpaceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=SpaceRepository::class)
 * @Vich\Uploadable
 */
class Space
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Le champ nom ne peut être vide")
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=500, nullable="true")
     * @Assert\NotBlank(message="Le champ photos ne peut être vide")
     *  @var string $photos
     */
    private $photos;

     /**
      * @Vich\UploadableField(mapping="photos_file", fileNameProperty="photos")
      * @var File
      */
    private ?File $photosFile;

    /**
     * @ORM\Column(type="integer", length=10)
    * @Assert\NotBlank(message="Le champ surface ne peut être vide")
     */
    private int $surface;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le champ catégorie ne peut être vide")
     */
    private string $category;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Le champ capacité ne peut être vide")
     */
    private int $capacity;

    /**
     * @ORM\Column(type="string", length=500)
     * @Assert\NotBlank(message="Le champ localisation ne peut être vide")
     */
    private string $location;

    /**
     * @ORM\OneToMany(targetEntity=Slot::class, mappedBy="space", orphanRemoval=true)
     */
    private Collection $slots;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="spaces")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $owner;

    /**
     * @ORM\Column(type="integer")
     */
    private int $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $description;

    /**
     * @ORM\OneToOne(targetEntity=SpaceDisponibility::class, mappedBy="space", cascade={"persist", "remove"})
     */
    private SpaceDisponibility $spaceDisponibility;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->photosFile = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPhotos(): ?string
    {
        return $this->photos;
    }

    public function setPhotos(string $photos): self
    {
        $this->photos = $photos;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): self
    {
        $this->surface = $surface;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): self
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return Collection|Slot[]
     */
    public function getSlots(): Collection
    {
        return $this->slots;
    }

    public function addSlot(Slot $slot): self
    {
        if (!$this->slots->contains($slot)) {
            $this->slots[] = $slot;
            $slot->setSpace($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getSpace() === $this) {
                $slot->setSpace(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getSpaceDisponibility(): ?SpaceDisponibility
    {
        return $this->spaceDisponibility;
    }

    public function setSpaceDisponibility(SpaceDisponibility $spaceDisponibility): self
    {
        // unset the owning side of the relation if necessary
        if ($spaceDisponibility == null && $this->spaceDisponibility !== null) {
            $this->spaceDisponibility->setSpace(null);
        }

        // set the owning side of the relation if necessary
        if ($spaceDisponibility !== null && $spaceDisponibility->getSpace() !== $this) {
            $spaceDisponibility->setSpace($this);
        }

        $this->spaceDisponibility = $spaceDisponibility;

        return $this;
    }

    public function setPhotosFile(?File $photos = null): Space
    {
        $this->photosFile = $photos;
        return $this;
    }

    public function getPhotosFile(): ?File
    {
        return $this->photosFile;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }
}
