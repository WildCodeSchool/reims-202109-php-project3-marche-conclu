<?php

namespace App\Controller;

use App\Form\SearchType;
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

        if ($location == null) {
            $spaces = $spaceRepository->findAll();
        } else {
            $spaces = $spaceRepository->findByLocation($location);
        }

        return $this->renderForm('home/index.html.twig', [
            'location' => $location,
            'spaces' => $spaces,
            'categories' => self::CATEGORIES
        ]);
    }
}
