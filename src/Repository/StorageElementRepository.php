<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Repository;

use DevRunDebug\MediaCatalog\Entity\StorageElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class StorageElementRepository extends ServiceEntityRepository
{
    public function __construct(\Doctrine\Persistence\ManagerRegistry $registry)
    {
        parent::__construct($registry, StorageElement::class);
    }

    public function findOneByLocalizationAndChecksum(string $path, ?string $checksum): ?StorageElement
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->where('f.path = :path')
            ->setMaxResults(1);

        $bindParams = [
            'path' => $path,
        ];

        if ($checksum) {
            $queryBuilder->andWhere('f.checksum = :checksum');

            $bindParams['checksum'] = $checksum;
        }

        $queryBuilder->setParameters($bindParams);

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public function findAllByChecksum(string $checksum)
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->where('f.checksum = :checksum');

        $queryBuilder->setParameters([
            'checksum' => $checksum,
        ]);

        return $queryBuilder->getQuery()->getResult();
    }
}
