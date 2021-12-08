<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\SpaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpaceController extends AbstractController
{

    /**
     * @Route("/space/{location}", name="app_space_list")
     */

    public function list(SpaceRepository $spaceRepository, string $location): Response
    {

        $space = $spaceRepository->findByLocation($location);
        return $this->render('search/list.html.twig', [
            'space' => $space,
        ]);
    }
}
