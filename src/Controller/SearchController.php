<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\SpaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{


    /**
     * @Route("/", name="app_search")
     */

    public function index(Request $request): Response
    {

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $form->get('location')->getData();

            return $this->redirectToRoute('app_search_list', [
                'location' => $location,
            ]);
        }

        return $this->renderForm('search/index.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/search/{location}", name="app_search_list")
     */
    public function list(SpaceRepository $spaceRepository, string $location): Response
    {

        $space = $spaceRepository->findByLocation($location);
        return $this->render('search/list.html.twig', [
            'space' => $space,
        ]);
    }
}
