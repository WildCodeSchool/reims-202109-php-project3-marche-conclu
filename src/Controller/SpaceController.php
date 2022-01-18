<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Entity\Slot;
use App\Entity\Space;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\User;
use App\Form\SlotType;
use App\Form\SpaceType;
use App\Repository\SlotRepository;
use App\Repository\SpaceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Flasher\Toastr\Prime\ToastrFactory;

#[Route('/space', name: 'space_')]
class SpaceController extends AbstractController
{
    public const CATEGORIES = [
        'BUREAU PRIVEE',
        'CO-WORKING',
        'SALLE DE REUNION',
        'OPEN SPACE',
        'ESPACE DE STOCKAGE',
    ];

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(SpaceRepository $spaceRepository): Response
    {
        return $this->render('space/index.html.twig', [
            'spaces' => $spaceRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ToastrFactory $flasher): Response
    {

        $space = new Space();
        $form = $this->createForm(SpaceType::class, $space);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $space->setOwner($user);
            $space->setPhoto('');
            $entityManager->persist($space);
            $entityManager->flush();
            $flasher->addSuccess('Votre annonce a bien été crée !');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('space/new.html.twig', [
            'space' => $space,
            'form' => $form,
            'api' => $_ENV['API_KEY']
        ]);
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(UserRepository $userRepository, Request $request, SpaceRepository $spaceRepository): Response
    {
        $users = $userRepository->findAll();
        $jobs = [];
        foreach ($users as $user) {
            if (!in_array($user->getJob(), $jobs)) {
                $jobs[] = $user->getJob();
            }
        }

        $options = $request->query->all();
        foreach ($options as $key => $option) {
            if ($option === "" || $option == 0) {
                unset($options[$key]);
            }
            if ($option === "on" or $option === "category") {
                $options['category'] = $key;
                unset($options[$key]);
            }
        }
        $spaces = $options ? $spaceRepository->findByCriterias($options) : $spaceRepository->findAll();
        return $this->renderForm('space/search.html.twig', [
            'location' => $options['location'] ?? null,
            'spaces' => $spaces, 'categories' => self::CATEGORIES,
            'api' => $_ENV['API_KEY'],
            'jobs' => $jobs,
            'options' => $options
            ]);
    }

    #[Route('/{id}', name: 'show', methods: ['POST', 'GET'])]
    public function show(
        Request $request,
        EntityManagerInterface $entityManager,
        Space $space,
        SlotRepository $slotrepository,
        ToastrFactory $flasher
    ): Response {

        $slot = new Slot();
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);
        $user = $this->getUser();
        $availability = array_map("trim", explode(',', $space->getAvailability() ?? ""));

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */

            $slottime = $form->get('slotTime')->getData();

            $reservations = array_map('trim', explode(',', strval($form->get('slotTime')->getData())));
            if (!empty($slottime) && $slottime !== null) {
                foreach ($reservations as $reservation) {
                    $slot = new Slot();
                    $slot->setOwner($user);
                    $slot->setSpace($space);
                    $slot->setPrice(0);
                    $slot->setSlotTime($reservation);
                    $key = array_search($reservation, $availability);
                    unset($availability[$key]);
                    $entityManager->persist($slot);
                }
            } else {
                $flasher->addError('Veuillez renseigner une date !');
                return $this->redirectToRoute('space_show', ['id' => $space->getId()], Response::HTTP_SEE_OTHER);
            }
            $space->setAvailability(implode(", ", $availability));
            $entityManager->flush();

            $flasher->addSuccess('Votre réservation a été enregistré !');

            return $this->redirectToRoute('space_show', ['id' => $space->getId()], Response::HTTP_SEE_OTHER);
        }


        return $this->renderForm('space/show.html.twig', [
            'space' => $space,
            'slot' => $slot,
            'form' => $form,
            'availability' => $availability
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Space $space,
        EntityManagerInterface $entityManager,
        ToastrFactory $flasher
    ): Response {
        $form = $this->createForm(SpaceType::class, $space);
        $form->handleRequest($request);

        $availability = $space->getAvailability();
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $flasher->addSuccess('Votre réservation a été modifiée !');

            return $this->redirectToRoute('space_show', ['id' => $space->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('space/edit.html.twig', [
            'space' => $space,
            'form' => $form,
            'availability' => $availability,
        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Space $space,
        EntityManagerInterface $entityManager,
        ToastrFactory $flasher
    ): Response {

        if ($this->isCsrfTokenValid('delete' . $space->getId(), strval($request->request->get('_token')))) {
            $entityManager->remove($space);
            $entityManager->flush();
            $flasher->addSuccess('Votre réservation a été supprimée !');
        }

        return $this->redirectToRoute('space_index', [], Response::HTTP_SEE_OTHER);
    }
}
