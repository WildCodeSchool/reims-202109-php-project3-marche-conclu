<?php

namespace App\Controller;

use App\Entity\Images;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ImagesController extends AbstractController
{
    #[Route('/images', name: 'images')]
    public function index(): Response
    {
        return $this->render('images/index.html.twig', [
            'controller_name' => 'ImagesController',
        ]);
    }

    //     /**
    //  * @IsGranted("ROLE_USER")
    //  */
    // #[Route('/image/delete/{id}', name: 'image_delete', methods: ['DELETE'])]
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
