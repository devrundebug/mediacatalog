<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Controller;

use DevRunDebug\MediaCatalog\Library\Version;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class VersionController
{
    private Version $version;

    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    #[Route('/version')]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            [
                'app' => 'monkey',
                'version' => $this->version->getAppVersion(),
            ],
            200
        );
    }
}
