<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Cli;

use DevRunDebug\MediaCatalog\Service\ScannerStorageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[\AllowDynamicProperties] #[AsCommand(name: 'app:scan-files')]
class ScanStorageCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private array $appConfig;

    private ScannerStorageService $scannerStorageService;

    public function __construct(
        EntityManagerInterface $entityManager,
        ScannerStorageService $fileService,
        ParameterBagInterface $containerBag
    ) {
        $this->entityManager = $entityManager;
        $this->appConfig = $containerBag->get('appConfig');
        $this->scannerStorageService = $fileService;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->scannerStorageService->scan();

        return Command::SUCCESS;
    }
}
