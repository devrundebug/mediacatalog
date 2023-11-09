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
    #[Gedmo\Timestampable]
    private ?\DateTimeInterface $updated_at;
}
