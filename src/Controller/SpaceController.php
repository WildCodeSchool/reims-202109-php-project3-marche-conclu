<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Entity\Space;
use App\Form\SpaceType;
use App\Repository\SpaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpaceController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, SpaceRepository $spaceRepository): Response
    {

        $form = $this->createForm(SearchType::class, null, array('method' => 'GET'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $location = $form->get('location')->getData();

            return $this->render('space/list.html.twig', [
                'location' => $location,
                'spaces' => $spaceRepository->findByLocation($location)
            ]);
        }

        return $this->renderForm('space/index.html.twig', ['form' => $form]);
    }

    #[Route('/new', name: 'space_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $space = new Space();
        $form = $this->createForm(SpaceType::class, $space);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($space);
            $entityManager->flush();

            return $this->redirectToRoute('space_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('space/new.html.twig', [
            'space' => $space,
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
    #[Route('/{id}', name: 'space_show', methods: ['GET'])]
    public function show(Space $space): Response
    {
        return $this->render('space/show.html.twig', [
            'space' => $space,
        ]);
    }

    #[Route('/{id}/edit', name: 'space_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Space $space, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpaceType::class, $space);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('space_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('space/edit.html.twig', [
            'space' => $space,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'space_delete', methods: ['POST'])]
    public function delete(Request $request, Space $space, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete' . $space->getId(), strval($request->request->get('_token')))) {
            $entityManager->remove($space);
            $entityManager->flush();
        }

        return $this->redirectToRoute('space_index', [], Response::HTTP_SEE_OTHER);
    }
}
