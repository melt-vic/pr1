<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CategoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavigationController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly CategoryService $categoryService
    ) {
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('front/home.html.twig');
    }

    #[Route('/categoria/{name}/{id}', name: 'category', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function category(int $id): Response
    {
        $category = $this->categoryService->getCategory($id);
        if (empty($category)) {
            throw $this->createNotFoundException('La categoría no existe');
        }
        $products = $this->productRepository->findByCategory($id);

        return $this->render('front/category-products.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }

    #[Route('/producto/{name}/{id}', name: 'product', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function product(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if (empty($product)) {
            throw $this->createNotFoundException('El producto no existe');
        }

        return $this->render('front/product.html.twig', [
            'product' => $product,
        ]);
    }
}
