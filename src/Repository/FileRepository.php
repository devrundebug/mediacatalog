<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Repository;

use DevRunDebug\MediaCatalog\Entity\File;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class FileRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function findOneByLocalizationAndChecksum(string $localization, string $checksum): ?File
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->where('f.localization = :localization and f.checksum = :checksum')
            ->setMaxResults(1);

        $queryBuilder->setParameters([
            'localization' => $localization,
            'checksum' => $checksum,
        ]);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}
