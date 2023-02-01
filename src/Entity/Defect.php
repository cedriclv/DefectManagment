<?php

namespace App\Entity;

use App\Repository\DefectRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DefectRepository::class)]
class Defect
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $bin_number = null;

    #[ORM\Column(length: 255)]
    private ?string $item = null;

    #[ORM\Column(nullable: true)]
    private ?int $expected_quantity = null;

    #[ORM\Column(nullable: true)]
    private ?int $actual_quantity = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $attachment_link = null;

    #[ORM\ManyToOne(inversedBy: 'defects', fetch : 'EAGER')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Count $count = null;

    #[ORM\Column]
    private ?bool $isInvestigated = null;

    #[ORM\ManyToOne(inversedBy: 'defects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reason $reason = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBinNumber(): ?string
    {
        return $this->bin_number;
    }

    public function setBinNumber(string $bin_number): self
    {
        $this->bin_number = $bin_number;

        return $this;
    }

    public function getItem(): ?string
    {
        return $this->item;
    }

    public function setItem(string $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getExpectedQuantity(): ?int
    {
        return $this->expected_quantity;
    }

    public function setExpectedQuantity(?int $expected_quantity): self
    {
        $this->expected_quantity = $expected_quantity;

        return $this;
    }

    public function getActualQuantity(): ?int
    {
        return $this->actual_quantity;
    }

    public function setActualQuantity(?int $actual_quantity): self
    {
        $this->actual_quantity = $actual_quantity;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getAttachmentLink(): ?string
    {
        return $this->attachment_link;
    }

    public function setAttachmentLink(?string $attachment_link): self
    {
        $this->attachment_link = $attachment_link;

        return $this;
    }

    public function getCount(): ?Count
    {
        return $this->count;
    }

    public function setCount(?Count $count): self
    {
        $this->count = $count;

        return $this;
    }

    public function isIsInvestigated(): ?bool
    {
        return $this->isInvestigated;
    }

    public function setIsInvestigated(bool $isInvestigated): self
    {
        $this->isInvestigated = $isInvestigated;

        return $this;
    }

    public function getReason(): ?Reason
    {
        return $this->reason;
    }

    public function setReason(?Reason $reason): self
    {
        $this->reason = $reason;

        return $this;
    }
}
