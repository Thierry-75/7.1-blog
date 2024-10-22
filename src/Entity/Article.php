<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?User $user = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'articles')]
    private Collection $categorie;

    /**
     * @var Collection<int, Keyword>
     */
    #[ORM\ManyToMany(targetEntity: Keyword::class, inversedBy: 'articles')]
    private Collection $keyword;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'article', orphanRemoval: true)]
    private Collection $comments;

    /**
     * @var Collection<int, Photo>
     */
    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'article', orphanRemoval: true)]
    private Collection $photos;

    public function __construct()
    {
        $this->categorie = new ArrayCollection();
        $this->keyword = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    /**
     * @return Collection<int, Category>
     */
    public function getCategorie(): Collection
    {
        return $this->categorie;
    }

    public function addCategorie(Category $categorie): static
    {
        if (!$this->categorie->contains($categorie)) {
            $this->categorie->add($categorie);
        }

        return $this;
    }

    public function removeCategorie(Category $categorie): static
    {
        $this->categorie->removeElement($categorie);

        return $this;
    }

    /**
     * @return Collection<int, Keyword>
     */
    public function getKeyword(): Collection
    {
        return $this->keyword;
    }

    public function addKeyword(Keyword $keyword): static
    {
        if (!$this->keyword->contains($keyword)) {
            $this->keyword->add($keyword);
        }

        return $this;
    }

    public function removeKeyword(Keyword $keyword): static
    {
        $this->keyword->removeElement($keyword);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getArticle() === $this) {
                $comment->setArticle(null);
            }
        }

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
