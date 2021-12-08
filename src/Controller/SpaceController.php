<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Space;
use App\Form\SpaceType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SpaceController extends AbstractController
{
    /**
     * @Route("/space/new", name="app_add_space")
     */

    public function new(Request $request): Response
    {
        $spaceForm = new Space();

        $form = $this->createForm(SpaceType::class, $spaceForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($spaceForm);
            $entityManager->flush();

            return $this->redirectToRoute('app_search');
        }

        return $this->renderForm('space/new.html.twig', [
            'form' => $form,
        ]);
    }
}
