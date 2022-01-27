<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @Vich\Uploadable

 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Le champ email ne peut être vide")
     */
    private string $email;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity=Slot::class, mappedBy="owner", orphanRemoval=true)
     */
    private Collection $slots;

    /**
     * @ORM\OneToMany(targetEntity=Space::class, mappedBy="owner", orphanRemoval=true)
     */
    private Collection $spaces;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le champ prénom ne peut être vide")
     */
    private string $firstname;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank(message="Le champ nom de famille ne peut être vide")
     */
    private string $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $photo;

    /**
     * @Vich\UploadableField(mapping="poster_file", fileNameProperty="photo")
     * @Assert\File(
     * maxSize = "1M",
     * mimeTypes = {"image/jpeg", "image/png", "image/webp"},
     * )
     * @var File
     */
    private $photoFile;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updatedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $company;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private ?string $job;
/**
     * @ORM\Column(type="string", length=15)
     */
    private string $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $slug;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender", orphanRemoval=true)
     */
    private Collection $sent;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="receiver", orphanRemoval=true)
     */
    private Collection $received;

    public function __construct()
    {
        $this->slots = new ArrayCollection();
        $this->spaces = new ArrayCollection();
        $this->sent = new ArrayCollection();
        $this->received = new ArrayCollection();
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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $slot->setOwner($this);
        }

        return $this;
    }

    public function removeSlot(Slot $slot): self
    {
        if ($this->slots->removeElement($slot)) {
            // set the owning side to null (unless already changed)
            if ($slot->getOwner() === $this) {
                $slot->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Space[]
     */
    public function getSpaces(): Collection
    {
        return $this->spaces;
    }

    public function addSpace(Space $space): self
    {
        if (!$this->spaces->contains($space)) {
            $this->spaces[] = $space;
            $space->setOwner($this);
        }

        return $this;
    }

    public function removeSpace(Space $space): self
    {
        if ($this->spaces->removeElement($space)) {
            // set the owning side to null (unless already changed)
            if ($space->getOwner() === $this) {
                $space->setOwner(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(string $job): self
    {
        $this->job = $job;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * Get the value of photo
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * Set the value of photo
     */
    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get the value of photoFile
     */
    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    /**
     * Set the value of photoFile
     */
    public function setPhotoFile(File $image): User
    {
        $this->photoFile = $image;
        $this->updatedAt = new DateTime('now');

        return $this;
    }

    /**
     * Get the value of updatedAt
     *
     * @return DateTimeInterface
     */
    public function getUpdatedAt(): DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * Set the value of updatedAt
     *
     * @param DateTimeInterface $updatedAt
     *
     * @return self
     */
    public function setUpdatedAt(DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;


        return $this;
    }

    /**
     * @return Collection|Message[]
     */

    public function getSent(): ?Collection
    {
        return $this->sent;
    }

    public function addSent(Message $sent): self
    {
        if (!$this->sent->contains($sent)) {
            $this->sent[] = $sent;
            $sent->setSender($this);
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceived(): ?Collection
    {
        return $this->received;
    }

    public function addReceived(Message $received): self
    {
        if (!$this->received->contains($received)) {
            $this->received[] = $received;
            $received->setReceiver($this);
        }

        return $this;
    }
}
