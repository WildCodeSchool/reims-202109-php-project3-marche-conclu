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


    #[Route('/contact', name: 'contact')]
    public function contact(): Response
    {
        return $this->renderForm('contact.html.twig');
    }

    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('about.html.twig');
    }

     #[Route('/premium', name: 'premium')]
    public function premium(): Response
    {
        return $this->render('premium.html.twig');
    }
}
