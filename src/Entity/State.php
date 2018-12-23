<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StateRepository")
 */
class State
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     */
    private $name;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

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
    * Get the name
    *
    * @return string
    */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
    * Set the name
    *
    * @param string $name
    */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
}
