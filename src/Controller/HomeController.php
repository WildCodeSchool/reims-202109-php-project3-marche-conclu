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
        'BUREAU PRIVEE',
        'CO-WORKING',
        'SALLE DE REUNION',
        'OPEN SPACE',
        'ESPACE DE STOCKAGE',
    ];

    #[Route('/', name: 'home')]
    public function index(Request $request, SpaceRepository $spaceRepository): Response
    {
        $location = $request->query->get('location');

        $spaces = $location ? $spaceRepository->findByLocation($location) : $spaceRepository->findAll();

        return $this->renderForm('home/index.html.twig', [
            'location' => $location,
            'spaces' => $spaces,
            'categories' => self::CATEGORIES
        ]);
    }
}
