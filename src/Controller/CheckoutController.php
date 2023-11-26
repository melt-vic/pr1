<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserAnonymousType;
use App\Form\UserRegisteredType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckoutController extends AbstractController
{
    #[Route('/checkout', name: 'checkout', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $user = new User();
        $formAnonymous = $this->createForm(UserAnonymousType::class, $user);
        $formRegistered = $this->createForm(UserRegisteredType::class, $user);

        return $this->render('front/checkout.html.twig', [
            'formAnonymous' => $formAnonymous,
            'formRegistered' => $formRegistered,
        ]);
    }
}
