<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks
 *
 * @ApiResource(
 *     collectionOperations={"get", "post"},
 *     itemOperations={"put", "delete"},
 * )
 *
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * The product's visible name.
     *
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * The product's price.
     *
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $price;

    /**
     * The average rating the users had given to the product.
     *
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Assert\Range(
     *     min = 0,
     *     max = 5,
     *     notInRangeMessage = "The average rating must be between {{ min }}  and {{ max }}."
     * )
     */
    private $averageRating;

    /**
     * @ORM\OneToMany(targetEntity="Rating", mappedBy="products")
     */
    private $ratings;

    /**
     *  Total number of rating votes.
     *
     * @ORM\Column(type="integer")
     */
    private $numberOfVotes;

    /**
     * Variable set of properties.
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private $variations;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function getAverageRating(): float
    {
        return $this->averageRating;
    }

    public function setAverageRating($averageRating): void
    {
        $this->averageRating = $averageRating;
    }

    public function getVariations(): ?string
    {
        return $this->variations;
    }

    public function setVariations($variations): void
    {
        $this->variations = $variations;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updated_at;
    }

    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function setRatings($ratings): void
    {
        $this->ratings = $ratings;
    }

    public function getNumberOfVotes(): Integer
    {
        return $this->numberOfVotes;
    }

    public function setNumberOfVotes($numberOfVotes): void
    {
        $this->numberOfVotes = $numberOfVotes;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamp(): void
    {
        $this->updated_at = new \DateTime('now');
        if ($this->created_at === null) {
            $this->created_at = $this->updated_at;
        }
    }

    public function vote(int $rating): void
    {
        $this->averageRating = round((($this->averageRating * $this->numberOfVotes) + $rating) / ($this->numberOfVotes + 1), 1);
        $this->numberOfVotes++;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'rating' => $this->getAverageRating()
        ];
    }
}
