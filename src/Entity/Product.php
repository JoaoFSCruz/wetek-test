<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     */
    private $price;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank
     * @Assert\Range(
     *     min = 0,
     *     max = 5,
     *     notInRangeMessage = "The rating must be between {{ min }}  and {{ max }}."
     * )
     */
    private $rating;

    /**
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

    public function getRating(): float
    {
        return $this->rating;
    }

    public function setRating($rating): void
    {
        $this->rating = $rating;
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

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'price' => $this->getPrice(),
            'rating' => $this->getRating(),
            'variations' => json_decode($this->getVariations(), true),
            'created_at' => $this->getCreatedAt(),
            'updated_at' => $this->getUpdatedAt()
        ];
    }
}
