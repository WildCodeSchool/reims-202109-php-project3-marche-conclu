<?php

namespace App\Controller;

use App\Repository\SpaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public const CATEGORIES = [
        'reunion' => 'Salle de reunion',
        'coworking' => 'Co-Working',
        'private' => 'Bureau privee',
        'open-space' => 'Open-Space',
        'stockage' => 'Plateaux vides'
    ];

    #[Route('/', name: 'home')]
    public function index(SpaceRepository $spaceRepository): Response
    {

        $spaces = $spaceRepository->findBy(array(), null, 2);

        return $this->renderForm('home/index.html.twig', [
            'spaces' => $spaces,
            'categories' => self::CATEGORIES,
            'api' => $_ENV["API_KEY"]
        ]);
    }

    #[Route('/premium', name: 'premium')]
    public function premium(): Response
    {
        return $this->renderForm('premiumoffer.html.twig');
    }
}
