<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Impossible de céer un compte avec cet email.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[Assert\NotBlank(
        message : "L'email est obligatoire."
    )]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "L'email doit contenir au minimum {{ limit }} charactères.",
        maxMessage: "L'email doit contenir au maximum {{ limit }} charactères.",
    )]
    #[Assert\Email(
        message: "Veillez entrer un email valide.",
    )]
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;



    #[ORM\Column]
    private array $roles = [];

    
    /**
     * @var string The hashed password
     */
    #[Assert\NotBlank(
        message : "Le mot de passe est obligatoire."
    )]
    #[Assert\Length(
        min: 14,
        max: 255,
        minMessage: "Le mot de passe doit contenir au minimum {{ limit }} charactères.",
        maxMessage: "Le mot de passe doit contenir au maximum {{ limit }} charactères.",
    )]
    #[Assert\Regex(
        pattern: "#^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ỳ])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ỳ0-9]).{8,255}$#",
        match: true,
        message: 'Ce mot de passe doit contenir au moins une lettre minuscule, une lettre majuscule, un chiffre et un caractère spécial.',
    )]
    #[Assert\NotCompromisedPassword(
        message : "Ce mot de passe est facilement piratable. Veillez en choisir un autre."
    )]
    #[ORM\Column]
    private ?string $password = null;




    #[Assert\NotBlank(
        message : "Le prénom est obligatoire."
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le prénom doit contenir au maximum {{ limit }} charactères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;



    #[Assert\NotBlank(
        message : "Le nom est obligatoire."
    )]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom doit contenir au maximum {{ limit }} charactères.",
    )]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;



    #[ORM\Column]
    private ?bool $isVerified = null;



    #[ORM\Column(length: 255, nullable: true)]
    private ?string $tokenForEmailVerification = null;



    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deadLineForEmailVerification = null;



    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;



    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;



    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class, orphanRemoval: true)]
    private Collection $posts;


    

    public function __construct()
    {
        $this->isVerified  = false; 
        $this->roles[] = "ROLE_USER";
        $this->posts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getTokenForEmailVerification(): ?string
    {
        return $this->tokenForEmailVerification;
    }

    public function setTokenForEmailVerification(?string $tokenForEmailVerification): self
    {
        $this->tokenForEmailVerification = $tokenForEmailVerification;

        return $this;
    }

    public function getDeadLineForEmailVerification(): ?\DateTimeImmutable
    {
        return $this->deadLineForEmailVerification;
    }

    public function setDeadLineForEmailVerification(?\DateTimeImmutable $deadLineForEmailVerification): self
    {
        $this->deadLineForEmailVerification = $deadLineForEmailVerification;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }
    

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeImmutable $verifiedAt): self
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }
    
}
