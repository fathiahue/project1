<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\PublishAnnonceType;
use App\Entity\Annonce;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PublishAnnonceController extends AbstractController
{
    /**
     * @Route("/publishannonce", name="publishannonce")
     */
    public function index(Request $request,EntityManagerInterface $entityManager,UserRepository $userRepository): Response
    {
        if ($this->getUser() !== null) {

            /** @var \App\Entity\User $user */
            $user = $this->getUser();
        }
        $annonce = new Annonce();
        $annonce->setUser($user);
        $form = $this->createForm(PublishAnnonceType::class,$annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($annonce);
        $entityManager->flush();
        $annonceid = $annonce->getId();
        $this->addFlash('success', 'Vore annonce a été publier');
        return $this->redirectToRoute('annonce');
        }
        return $this->render('publishannonce/index.html.twig', [
            'user' => $user,
            'form' => $form->createView()
        ]);
    }
}
