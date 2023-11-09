<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Library;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Version
{
    private ?string $appVersion = null;

    /**
     * @throws \Exception
     */
    public function __construct(ParameterBagInterface $parameterBag)
    {
        if (!is_array($parameterBag->get('appConfig'))) {
            throw new \Exception();
        }

        if (!isset($parameterBag->get('appConfig')['versionFile'])) {
            throw new \Exception();
        }

        $projectDir = $parameterBag->get('kernel.project_dir');
        $versionFileName = $parameterBag->get('appConfig')['versionFile'];

        if (!is_string($projectDir) || !is_string($versionFileName)) {
            throw new \Exception();
        }

        $filePath = sprintf(
            '%s/%s',
            $projectDir,
            $versionFileName
        );
        $this->appVersion = file_get_contents($filePath);
    }

    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }
}
