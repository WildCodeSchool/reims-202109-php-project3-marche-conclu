<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Repository\SpaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Config\Definition\Exception\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Flasher\Toastr\Prime\ToastrFactory;

#[Route('/user')]
class UserController extends AbstractController
{
    /**
    * @IsGranted("ROLE_USER")
    */
    #[Route('/profile', name: 'user_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('user/index.html.twig');
    }

    /**
    * @IsGranted("ROLE_USER")
    */
    #[Route('/{id}/edit', name: 'user_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        User $user,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        ToastrFactory $flasher
    ): Response {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $user->setPassword(
                    strval($userPasswordHasher->hashPassword(
                        $user,
                        strval($form->get('plainPassword')->getData())
                    )),
                );
                $entityManager->persist($user);
                $entityManager->flush();
                $flasher->addSuccess('Votre profil utilisateur a été modifié !');

                return $this->redirectToRoute('user_index');
            } catch (Exception $e) {
                $flasher->addError("Les modifications n'ont pas fonctionnées. Veuillez réessayer!");
            }
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
