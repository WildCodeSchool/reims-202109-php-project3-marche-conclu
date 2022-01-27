<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\UserRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MessageController extends AbstractController
{
    /**
     * @Route("/messages", name="messages")
     */
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'messageController',
        ]);
    }

    /**
     * @Route("{slug}/send", name="send")
     */
    public function send(
        UserRepository $userRepository,
        string $slug,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \App\Entity\User $user */
            $user = $this->getUser();
            $message->setReceiver($userRepository->findOneBySlug($slug));
            $message->setSender($user);
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash("message", "Message envoyé avec succès.");
            return $this->redirectToRoute("messages");
        }

        return $this->renderForm("message/send.html.twig", [
            "form" => $form
        ]);
    }

    /**
     * @Route("/received", name="received")
     */
    public function received(): Response
    {
        return $this->render('message/received.html.twig');
    }


    /**
     * @Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('message/sent.html.twig');
    }
}
