<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Controller\Api;

use DevRunDebug\MediaCatalog\Library\Version;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class GetApiVersionController
{
    private Version $version;

    public function __construct(Version $version)
    {
        $this->version = $version;
    }

    #[Route('/api/version', name: 'api_get_version', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse(
            [
                'app' => 'media-catalog',
                'version' => $this->version->getAppVersion(),
            ],
            200
        );
    }
}
