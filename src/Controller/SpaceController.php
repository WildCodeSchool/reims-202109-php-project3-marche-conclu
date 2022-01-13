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
use Flasher\Prime\FlasherInterface;
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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $space = new Space();
        $form = $this->createForm(SpaceType::class, $space);
        $form->handleRequest($request);
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $space->setOwner($user);
            $space->setPhotos('');
            $entityManager->persist($space);
            $entityManager->flush();

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('space/new.html.twig', [
            'space' => $space,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request, SpaceRepository $spaceRepository): Response
    {
        $options = $request->query->all();
        foreach ($options as $key => $option) {
            if ($option === "") {
                unset($options[$key]);
            }
            if ($option === "on") {
                $options['category'] = $key;
                unset($options[$key]);
            }
        }
        $spaces = $options ? $spaceRepository->findByCriterias($options) : $spaceRepository->findAll();

        return $this->renderForm('space/search.html.twig', [
            'location' => $options['location'] ?? null,
            'spaces' => $spaces, 'categories' => self::CATEGORIES,
            'api' => $_ENV['API_KEY']
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['POST', 'GET'])]
    public function show(
        Request $request,
        EntityManagerInterface $entityManager,
        Space $space,
        User $user,
        SlotRepository $slotrepository,
        ToastrFactory $flasher
    ): Response {
        $slot = new Slot();
        $form = $this->createForm(SlotType::class, $slot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slot->setOwner($user);
            $slot->setSpace($space);
            $slot->setPrice(0);
            $entityManager->persist($slot);

            if ($slotrepository->findBy(["slotTime" => $slot->getSlotTime(), "space" => $slot->getSpace()])) {
                $flasher->addError("Votre réservation ne peut être enregistré. Ce créneau est indisponible.");
            } else {
                $flasher->addSuccess('Votre réservation a été enregistré');
                $entityManager->flush();
            }

            return $this->redirectToRoute('space_show', ['id' => $space->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('space/show.html.twig', [
            'space' => $space,
            'slot' => $slot,
            'form' => $form

        ]);
    }

    /**
     * @IsGranted("ROLE_USER")
     */
    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
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

    /**
     * @IsGranted("ROLE_USER")
     */
    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Space $space, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete' . $space->getId(), strval($request->request->get('_token')))) {
            $entityManager->remove($space);
            $entityManager->flush();
        }

        return $this->redirectToRoute('space_index', [], Response::HTTP_SEE_OTHER);
    }
}
