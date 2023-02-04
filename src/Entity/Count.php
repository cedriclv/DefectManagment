<?php

namespace App\Entity;

use App\Repository\CountRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountRepository::class)]
class Count
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'count', targetEntity: Defect::class)]
    private Collection $defects;

    public function __construct()
    {
        $this->defects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

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
            $defect->setCount($this);
        }

        return $this;
    }

    public function removeDefect(Defect $defect): self
    {
        if ($this->defects->removeElement($defect)) {
            // set the owning side to null (unless already changed)
            if ($defect->getCount() === $this) {
                $defect->setCount(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->date->format('Y-m-d H:i:s');
    }    
}
