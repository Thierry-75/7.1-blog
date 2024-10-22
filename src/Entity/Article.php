<?php

namespace App\Entity;


use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ArticleRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createAt = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;


    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $first_paragraphe = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $second_paragraph = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $third_paragraph = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;


    public function __construct()
    {
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreateAt(): ?\DateTimeImmutable
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeImmutable $createAt): static
    {
        $this->createAt = $createAt;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->title;
    }

    public function setTitre(string $titre): static
    {
        $this->title = $titre;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getFirstParagraphe(): ?string
    {
        return $this->first_paragraphe;
    }

    public function setFirstParagraphe(string $first_paragraphe): static
    {
        $this->first_paragraphe = $first_paragraphe;

        return $this;
    }

    public function getSecondParagraph(): ?string
    {
        return $this->second_paragraph;
    }

    public function setSecondParagraph(string $second_paragraph): static
    {
        $this->second_paragraph = $second_paragraph;

        return $this;
    }

    public function getThirdParagraph(): ?string
    {
        return $this->third_paragraph;
    }

    public function setThirdParagraph(string $third_paragraph): static
    {
        $this->third_paragraph = $third_paragraph;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
