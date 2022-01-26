<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Entity\Space;
use App\Form\SlotType;
use App\Repository\SlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/slot')]
class SlotController extends AbstractController
{
    #[Route('/', name: 'slot_index', methods: ['GET'])]
    public function index(SlotRepository $slotRepository): Response
    {
        return $this->render('slot/index.html.twig', [
            'slots' => $slotRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'slot_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $slot = new Slot();
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($slot);
            $entityManager->flush();

            return $this->redirectToRoute('slot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slot/new.html.twig', [
            'slot' => $slot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'slot_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Slot $slot, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('slot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('slot/edit.html.twig', [
            'slot' => $slot,
            'form' => $form,
        ]);
    }

    #[Route('/{space}/{id}', name: 'slot_delete', methods: ['POST'])]
    public function delete(Request $request, Slot $slot, EntityManagerInterface $entityManager, Space $space): Response
    {
        if ($this->isCsrfTokenValid('delete' . $slot->getId(), strval($request->request->get('_token')))) {
            $availability = array_map("trim", explode(',', $space->getAvailability() ?? ""));
            array_push($availability, ", " . $slot->getSlotTime());
            $space->setAvailability(implode(", ", $availability));
            $entityManager->remove($slot);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [
            'space' => $space,
        ]);
    }

    // #[Route('/{id}/book', name: 'slot_book', methods: ['GET'])]
    // public function book(Slot $slot, EntityManagerInterface $entityManager, ToastrFactory $flasher): Response
    // {
    //     if ($slot->isBooked()) {
    //         $flasher->addError("Votre réservation ne peut être enregistrée ! Ce créneau est indisponible.");
    //     } else {
    //         $user = $this->getUser();
    //         $slot->setTenant($user);
    //         $flasher->addSuccess('Votre réservation a été enregistré !');

    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('space_show', ['id' => $slot->getSpace->getId()], Response::HTTP_SEE_OTHER);
    // }
}
