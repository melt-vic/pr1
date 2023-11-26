<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserType;
use App\Form\UserAnonymousType;
use App\Form\UserRegisteredType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $mr, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $formAnonymous = $this->createForm(UserAnonymousType::class, $user);
        $formRegistered = $this->createForm(UserRegisteredType::class, $user);
        $em = $mr->getManager();

        $formAnonymous->handleRequest($request);
        if ($formAnonymous->isSubmitted() && $formAnonymous->isValid()) {
            $user = $formAnonymous->getData();
            $user->setType($mr->getRepository(UserType::class)->find(1));
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('requestSummary');
        }

        $formRegistered->handleRequest($request);
        if ($formRegistered->isSubmitted() && $formRegistered->isValid()) {
            $user = $formRegistered->getData();
            $user->setType($mr->getRepository(UserType::class)->find(2));
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('requestSummary');
        }

        return $this->render('front/checkout.html.twig', [
            'formAnonymous' => $formAnonymous,
            'formRegistered' => $formRegistered,
        ]);
    }

    #[Route('/request-summary', name: 'requestSummary', methods: ['GET'])]
    public function requestSummary(): Response
    {
        return $this->render('front/request-summary.html.twig');
    }
}
