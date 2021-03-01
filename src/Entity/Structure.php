<?php

namespace App\Entity;

use App\Repository\StructureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StructureRepository::class)
 */
class Structure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="structure")
     */
    private $name;

    public function __construct()
    {
        $this->name = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getName(): Collection
    {
        return $this->name;
    }

    public function addName(User $name): self
    {
        if (!$this->name->contains($name)) {
            $this->name[] = $name;
            $name->setStructure($this);
        }

        return $this;
    }

    public function removeName(User $name): self
    {
        if ($this->name->removeElement($name)) {
            // set the owning side to null (unless already changed)
            if ($name->getStructure() === $this) {
                $name->setStructure(null);
            }
        }

        return $this;
    }
}
