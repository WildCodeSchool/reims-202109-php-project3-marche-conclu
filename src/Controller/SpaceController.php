<?php

namespace App\Controller;

use App\Entity\Slot;
use App\Entity\User;
use App\Entity\Space;
use App\Entity\Images;
use App\Form\SlotType;
use App\Form\SpaceType;
use App\Repository\SlotRepository;
use App\Repository\UserRepository;
use App\Repository\SpaceRepository;
use Flasher\Toastr\Prime\ToastrFactory;
use Symfony\Component\DomCrawler\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            // on récupère les images transmises
            $images = $form['images']->getData();

            // on boucle sur les images
            foreach ($images as $image) {
                // on génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // on copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('upload_directory'),
                    $fichier
                );

                // on stocke l'image dans la base de donnée (son nom)
                $img = new Images();
                $img->setName($fichier);
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

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $slot->setOwner($user);
            $slot->setSpace($space);
            $slot->setPrice(0);
            $entityManager->persist($slot);

            if ($slotrepository->findBy(["slotTime" => $slot->getSlotTime(), "space" => $slot->getSpace()])) {
                $flasher->addError("Votre réservation ne peut être enregistrée ! Ce créneau est indisponible.");
            } else {
                $flasher->addSuccess('Votre réservation a été enregistré !');
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
    public function edit(
        Request $request,
        Space $space,
        EntityManagerInterface $entityManager,
        ToastrFactory $flasher
    ): Response {
        $form = $this->createForm(SpaceType::class, $space);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère les images transmises
            $images = $form['images']->getData();

            // on boucle sur les images
            foreach ($images as $image) {
                // on génère un nouveau nom de fichier
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // on copie le fichier dans le dossier uploads
                $image->move(
                    $this->getParameter('upload_directory'),
                    $fichier
                );

                // on stocke l'image dans la base de donnée (son nom)
                $img = new Images();
                $img->setName($fichier);
                $space->addImage($img);
            }
            $entityManager->flush();
            $flasher->addSuccess('Votre réservation a été modifiée !');

            return $this->redirectToRoute('space_show', ['id' => $space->getId()], Response::HTTP_SEE_OTHER);
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

    // /**
    //  * @IsGranted("ROLE_USER")
    //  */
    // #[Route('/delete/image/{id}', name: 'delete_image', methods: ['DELETE'])]
    // public function deleteImage(Images $image, Request $request): Response
    // {
    //     $data = json_decode($request->getContent(), true);

    //     // on vérifie si le token est valide
    //     if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
    //         // on récupère le nom de l'image
    //         $nom = $image->getName();
    //         // on supprime le fichier
    //         unlink($this->getParameter('upload_directory') . '/' . $nom);
    //         // on supprime l'entrée de la base
    //         $del = $this->getDoctrine()->getManager();
    //         $del->remove($image);
    //         $del->flush();

    //         // on répond en json
    //         return new JsonResponse(['success' => 1]);
    //     } else {
    //         return new JsonResponse(['error' => 'Token Invalide'], 400);
    //     }
    // }
}
