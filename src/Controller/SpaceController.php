<?php

namespace App\Controller;

use App\Entity\Space;
use App\Form\SearchType;
use App\Form\SpaceType;
use App\Repository\SpaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/space')]
class SpaceController extends AbstractController
{


    #[Route('/', name: 'space_index', methods: ['GET'])]
    public function index(SpaceRepository $spaceRepository): Response
    {
        return $this->render('space/index.html.twig', [
            'spaces' => $spaceRepository->findAll(),
        ]);
    }
    /**
     * @Route("/search", name="space_search")
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

        return $this->renderForm('space/search.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/list", name="app_space_list")
     */

    public function list(SpaceRepository $spaceRepository, Request $request): Response
    {
        $location = $request->get('location');

        $space = $spaceRepository->findByLocation($location);
        return $this->render('space/list.html.twig', [
            'space' => $space,
        ]);
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
