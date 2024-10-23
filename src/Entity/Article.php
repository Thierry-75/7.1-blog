<?php

namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    private ?string $second_paragraphe = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $third_paragraphe = null;

    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'article', orphanRemoval: true,cascade: ['persist'])]
    private Collection $photos;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $titre): static
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

    public function getSecondParagraphe(): ?string
    {
        return $this->second_paragraphe;
    }

    public function setSecondParagraphe(string $second_paragraph): static
    {
        $this->second_paragraphe = $second_paragraph;

        return $this;
    }

    public function getThirdParagraphe(): ?string
    {
        return $this->third_paragraphe;
    }

    public function setThirdParagraphe(string $third_paragraph): static
    {
        $this->third_paragraphe = $third_paragraph;

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

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setArticle($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getArticle() === $this) {
                $photo->setArticle(null);
            }
        }

        return $this;
    }
}
