<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Service;

use DevRunDebug\MediaCatalog\Entity\StorageElement;
use DevRunDebug\MediaCatalog\Exception\BusinessLogicException;
use DevRunDebug\MediaCatalog\Repository\StorageElementRepository;
use DevRunDebug\MediaCatalog\Type\EnumLocalizationType;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use League\Flysystem\StorageAttributes;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ScannerStorageService
{
    private array $appConfig;

    private Filesystem $localFilesystem;

    private EntityManagerInterface $entityManager;

    private StorageElementRepository $fileRepository;

    public function __construct(
        ParameterBagInterface $containerBag,
        EntityManagerInterface $entityManager,
        StorageElementRepository $fileRepository
    ) {
        $this->appConfig = $containerBag->get('appConfig');
        $this->localFilesystem = new Filesystem(
            new LocalFilesystemAdapter($this->appConfig['process']['workdir'])
        );
        $this->entityManager = $entityManager;
        $this->fileRepository = $fileRepository;
    }

    public function scan(): void
    {
        $files = [];

        $this->scanDirectory('', $files, 1);

        foreach ($files as $file) {
            $this->entityManager->persist($file);
        }
        $this->entityManager->flush();
    }

    private function scanDirectory(string $path, array &$files, int $depth): void
    {
        $listContents = $this->localFilesystem->listContents($path, false);
        foreach ($listContents as $item) {
            $type = $item->isDir() ? EnumLocalizationType::TYPE_DIRECTORY : EnumLocalizationType::TYPE_FILE;

            $storageElement = $this->prepareStorageElement($item, $type, $depth);
            if ($this->registerFileInDB($storageElement)) {
                continue;
            }
            $files[] = $storageElement;

            if ($item->isDir()) {
                $this->scanDirectory($item->path(), $files, $depth + 1);
            }
        }
    }

    /**
     * @param string|EnumLocalizationType::* $type
     */
    private function prepareStorageElement(StorageAttributes $item, string $type, int $depth): StorageElement
    {
        $file = new StorageElement();
        $file->setPath($item->path())
        ->setType($type)
        ->setDepth($depth);

        if (EnumLocalizationType::TYPE_FILE === $type) {
            $file->setChecksum($this->localFilesystem->checksum($item->path()));
        }

        return $file;
    }

    private function registerFileInDB(StorageElement $file): bool
    {
        $path = $file->getPath();
        if ($path) {
            return (bool) $this->fileRepository->findOneByLocalizationAndChecksum(
                $path,
                $file->getChecksum()
            );
        }
        throw BusinessLogicException::create('Path is empty');
    }
}
