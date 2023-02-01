<?php

namespace App\Entity;

use App\Repository\ReasonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReasonRepository::class)]
class Reason
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'reason', targetEntity: Defect::class)]
    private Collection $defects;

    public function __construct()
    {
        $this->defects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Defect>
     */
    public function getDefects(): Collection
    {
        return $this->defects;
    }

    public function addDefect(Defect $defect): self
    {
        if (!$this->defects->contains($defect)) {
            $this->defects->add($defect);
            $defect->setReason($this);
        }

        return $this;
    }

    public function removeDefect(Defect $defect): self
    {
        if ($this->defects->removeElement($defect)) {
            // set the owning side to null (unless already changed)
            if ($defect->getReason() === $this) {
                $defect->setReason(null);
            }
        }

        return $this;
    }
}
