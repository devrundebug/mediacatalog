<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Entity;

use DevRunDebug\MediaCatalog\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: FileRepository::class)]
class File
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string')]
    private string $localization;

    #[ORM\Column(type: 'enum_localization_type')]
    private string $type;

    #[ORM\Column(type: 'string')]
    private string $checksum;

    #[ORM\Column(type: 'datetime', nullable: true, options: ['default' => 'CURRENT_TIMESTAMP', 'comment' => 'Creation date'])]
    #[Gedmo\Timestampable(on: 'create')]
    private ?\DateTimeInterface $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    #[Gedmo\Timestampable(on: 'update')]
    private ?\DateTimeInterface $updated_at;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getLocalization(): string
    {
        return $this->localization;
    }

    public function setLocalization(string $localization): self
    {
        $this->localization = $localization;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getChecksum(): string
    {
        return $this->checksum;
    }

    public function setChecksum(string $checksum): self
    {
        $this->checksum = $checksum;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }
}
