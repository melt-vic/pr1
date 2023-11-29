<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly CartService $cartService
    ) {
    }

    #[Route('/carrito', name: 'cart', methods: ['GET'])]
    public function index(Request $request): Response
    {
        return $this->render('front/cart.html.twig',
            ['cart' => $request->getSession()->get('cart')]);
    }

    #[Route('/carrito/agregar/{id}', name: 'cart_add', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function addToCart(int $id): Response
    {
        $product = $this->productRepository->find($id);
        if (empty($product)) {
            throw $this->createNotFoundException('El producto no existe');
        }
        $this->cartService->addToCart($id, $product);

        return $this->redirectToRoute('cart');
    }

    #[Route('/carrito/eliminar/{id}', name: 'cart_remove', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function removeFromCart(int $id): Response
    {
        $this->cartService->removeFromCart($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/carrito/eliminar-producto/{id}', name: 'cart_remove_product', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function removeProductFromCart(int $id): Response
    {
        $this->cartService->removeProductFromCart($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/vaciar-carrito', name: 'empty_cart', methods: ['GET'])]
    public function emptyCart(): Response
    {
        $this->cartService->emptyCart();

        return $this->redirectToRoute('cart');
    }
}
