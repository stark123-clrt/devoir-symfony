<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire.')]
    private ?string $title = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'Le contenu est obligatoire.')]
    private ?string $content = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isPublic = true;

    // Format : 2 lettres + 2 chiffres (ex: AA00, ER12)
    #[ORM\Column(type: 'string', length: 4, nullable: true)]
    #[Assert\Regex(
        pattern: '/^[A-Za-z]{2}[0-9]{2}$/',
        message: 'Le mot de passe doit contenir 2 lettres suivies de 2 chiffres (ex: AA00).'
    )]
    private ?string $notePassword = null;

    #[ORM\ManyToMany(targetEntity: self::class)]
    #[ORM\JoinTable(name: 'note_links')]
    private Collection $linkedNotes;

    public function __construct()
    {
        $this->createdAt    = new \DateTime();
        $this->linkedNotes  = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): self { $this->title = $title; return $this; }

    public function getContent(): ?string { return $this->content; }
    public function setContent(string $content): self { $this->content = $content; return $this; }

    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }

    public function getOwner(): ?User { return $this->owner; }
    public function setOwner(?User $owner): self { $this->owner = $owner; return $this; }

    public function isPublic(): bool { return $this->isPublic; }
    public function setIsPublic(bool $isPublic): self { $this->isPublic = $isPublic; return $this; }

    public function getNotePassword(): ?string { return $this->notePassword; }
    public function setNotePassword(?string $notePassword): self { $this->notePassword = $notePassword; return $this; }

    public function getLinkedNotes(): Collection { return $this->linkedNotes; }

    public function addLinkedNote(self $note): self
    {
        if (!$this->linkedNotes->contains($note)) {
            $this->linkedNotes->add($note);
        }
        return $this;
    }

    public function removeLinkedNote(self $note): self
    {
        $this->linkedNotes->removeElement($note);
        return $this;
    }
}
