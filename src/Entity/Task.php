<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\NotBlank
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $fkCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank
     */
    private $fkState;

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

    /**
    * Get the category
    *
    * @return Category
    */
    public function getFkCategory(): ?Category
    {
        return $this->fkCategory;
    }

    /**
    * Set the category
    *
    * @param Category $fkCategory
    */
    public function setFkCategory(?Category $fkCategory): self
    {
        $this->fkCategory = $fkCategory;

        return $this;
    }

    /**
    * Get the state
    *
    * @return State
    */
    public function getFkState(): ?State
    {
        return $this->fkState;
    }

    /**
    * Set the state
    *
    * @param State $fkState
    */
    public function setFkState(?State $fkState): self
    {
        $this->fkState = $fkState;

        return $this;
    }

}
