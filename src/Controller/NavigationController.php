<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CategoryService;

class NavigationController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('front/index.html.twig');
    }

    #[Route('/categoria/{name}/{id}', name: 'category', methods: ['GET'])]
    public function category(): Response
    {
        return $this->render('front/index.html.twig', [
            'products' => []
        ]);
    }
}