<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserAnonymousType;
use App\Form\UserRegisteredType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    public function __construct(private readonly UserService $userService)
    {
    }

    #[Route('/checkout', name: 'checkout', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $formAnonymous = $this->createForm(UserAnonymousType::class, $user);
        $formRegistered = $this->createForm(UserRegisteredType::class, $user);

        $formAnonymous->handleRequest($request);
        if ($formAnonymous->isSubmitted() && $formAnonymous->isValid()) {
            $user = $formAnonymous->getData();
            $this->userService->insertUser($user);

            return $this->redirectToRoute('requestSummary');
        }

        $formRegistered->handleRequest($request);
        if ($formRegistered->isSubmitted() && $formRegistered->isValid()) {
            $user = $formRegistered->getData();
            $this->userService->insertUser($user, false);

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
