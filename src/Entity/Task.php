<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkCategory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\State", inversedBy="tasks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $fkState;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getFkCategory(): ?Category
    {
        return $this->fkCategory;
    }

    public function setFkCategory(?Category $fkCategory): self
    {
        $this->fkCategory = $fkCategory;

        return $this;
    }

    public function getFkState(): ?State
    {
        return $this->fkState;
    }

    public function setFkState(?State $fkState): self
    {
        $this->fkState = $fkState;

        return $this;
    }

}
