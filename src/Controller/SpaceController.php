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
     * @Route("/", name="app_home")
     */
    public function search(Request $request): Response
    {

        $form = $this->createForm(SearchType::class, null, array('method' => 'GET'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $form->get('location')->getData();

            return $this->redirectToRoute('app_space_list', [
                'location' => $location,
            ]);
        }

        return $this->renderForm('search/index.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/search", name="app_space_list")
     */

    public function list(SpaceRepository $spaceRepository, Request $request): Response
    {
        $location = $request->get('location');

        $space = $spaceRepository->findByLocation($location);
        return $this->render('search/list.html.twig', [
            'space' => $space,
        ]);
    }
}
