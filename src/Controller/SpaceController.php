<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Entity\Space;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\SpaceDisponibility;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Entity\User;
use App\Entity\Image;
use App\Form\SlotType;
use App\Form\SpaceDisponibilityType;
use App\Form\SpaceType;
use App\Repository\SlotRepository;
use App\Repository\UserRepository;
use App\Repository\SpaceRepository;
use Flasher\Toastr\Prime\ToastrFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
            // on récupère les image transmises
            $images = $form['images']->getData();

            // on boucle sur les images
            foreach ($images as $image) {
                // on génère un nouveau nom de fichier
                $file = md5(uniqid()) . '.' . $image->guessExtension();

                // on copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('upload_directory'),
                    $file
                );

                // on stocke l'image dans la base de donnée (son nom)
                $img = new Image();
                $img->setName($file);
                $space->addImage($img);
            }
            /** @var \App\Entity\User $user */
            $space->setOwner($user);
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

    /**
    * @Route("/{id}/disponibility", name="space_disponibility")
    */
    public function addDisponibility(
        Space $space,
        Request $request,
        EntityManagerInterface $entityManager,
        SpaceRepository $spaceRepository,
        ToastrFactory $flasher
    ): Response {
        $spaceDisponibility = new SpaceDisponibility();
        $form = $this->createForm(SpaceDisponibilityType::class, $spaceDisponibility);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $spaceDisponibility->setSpace($space);
            $entityManager->persist($spaceDisponibility);
            $entityManager->flush();
            $flasher->addSuccess('Vos disponibilités ont bien été prises en compte');

            return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('space/newdisponibility.html.twig', [
            'form' => $form,
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
            if ($option === "" || $option == 0 || $option === "2022-01-15") {
                unset($options[$key]);
            }
            if ($option === "on" || $option === "category") {
                $options['category'] = $key;
                unset($options[$key]);
            }
        }
        return $this->renderForm('space/search.html.twig', [
            'location' => $options['location'] ?? null,
            'spaces' => $spaceRepository->findByCriterias($options),
            'categories' => self::CATEGORIES,
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
        $disponibility = $space->getSpaceDisponibility();
        $availability = array_map("trim", explode(',', $space->getAvailability() ?? ""));

        if ($form->isSubmitted() && $form->isValid()) {
            $slottime = $form->get('slotTime')->getData();

            $reservations = array_map('trim', explode(',', strval($form->get('slotTime')->getData())));
            if (!empty($slottime) && $slottime !== null) {
                foreach ($reservations as $reservation) {
                    $slot = new Slot();
                    /** @var \App\Entity\User $user */
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
            'disponibility' => $disponibility,
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
            // on récupère les images transmises
            $images = $form['images']->getData();

            // on boucle sur les images
            foreach ($images as $image) {
                // on génère un nouveau nom de fichier
                $file = md5(uniqid()) . '.' . $image->guessExtension();

                // on copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('upload_directory'),
                    $file
                );

                // on stocke l'image dans la base de donnée (son nom)
                $img = new Image();
                $img->setName($file);
                $space->addImage($img);
            }
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
