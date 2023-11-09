<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Service;

use DevRunDebug\MediaCatalog\Entity\File;
use DevRunDebug\MediaCatalog\Repository\FileRepository;
use DevRunDebug\MediaCatalog\Type\EnumLocalizationType;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\StorageAttributes;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FileService
{
    private array $appConfig;

    private Filesystem $localFilesystem;

    private EntityManagerInterface $entityManager;

    private FileRepository $fileRepository;

    public function __construct(
        ParameterBagInterface $containerBag,
        EntityManagerInterface $entityManager,
        FileRepository $fileRepository
    ) {
        $this->appConfig = $containerBag->get('appConfig');
        $this->localFilesystem = new Filesystem(
            new LocalFilesystemAdapter($this->appConfig['process']['workdir'])
        );
        $this->entityManager = $entityManager;
        $this->fileRepository = $fileRepository;
    }

    public function scanFiles(): void
    {
        $files = [];

        $this->scanDirectory('', $files);

        foreach ($files as $file) {
            $this->entityManager->persist($file);
        }
        $this->entityManager->flush();
    }

    private function scanDirectory(string $path, array &$files): void
    {
        $listContents = $this->localFilesystem->listContents($path, false);
        /** @var StorageAttributes $item */
        foreach ($listContents as $item) {
            if ($item->isDir()) {
                $this->scanDirectory($item->path(), $files);
            } else {
                $file = $this->prepareFileEntity($item);
                if ($this->fileRegistered($file)) {
                    continue;
                }
                $files[] = $file;
            }
        }
    }

    private function prepareFileEntity(StorageAttributes $item): File
    {
        $file = new File();
        $file->setLocalization($item->path());
        $file->setType(EnumLocalizationType::TYPE_FILE);
        $file->setChecksum($this->localFilesystem->checksum($item->path()));

        return $file;
    }

    private function fileRegistered(File $file): bool
    {
        return (bool) $this->fileRepository->findOneByLocalizationAndChecksum(
            $file->getLocalization(),
            $file->getChecksum()
        );
    }
}
