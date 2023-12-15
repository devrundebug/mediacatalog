<?php

declare(strict_types=1);

namespace DevRunDebug\MediaCatalog\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index', methods: ['GET'])]
    public function __invoke(): Response
    {
        return $this->render('index.html.twig');
    }
}
