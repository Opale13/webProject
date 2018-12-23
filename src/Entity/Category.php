<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank()
     */
    private $description;

    /**
    * Get the id
    *
    * @return int
    */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
    * Get the title
    *
    * @return string
    */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
    * Set the title
    *
    * @param string $title
    */
    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
    * Get the description
    *
    * @return string
    */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
    * Set the description
    *
    * @param string $description
    */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
