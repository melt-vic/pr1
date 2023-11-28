<?php

namespace App\Controller;

use App\Service\RequestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public function __construct(
        private readonly RequestService $requestService,
    ) {
    }

    #[Route('/admin', name: 'adminOrders', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('admin/orders-list.html.twig', [
            'requests' => $this->requestService->getAllRequests(),
        ]);
    }
}
