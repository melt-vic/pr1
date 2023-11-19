<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(
        private readonly ProductRepository $productRepository,
    ) {
    }

    #[Route('/carrito', name: 'cart', methods: ['GET'])]
    public function index(Request $request): Response
    {

        return $this->render('front/cart.html.twig',
            ['cart' => $request->getSession()->get('cart', [])]);
    }

    #[Route('/carrito/agregar/{id}', name: 'cart_add', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function addToCart(Request $request, $id): Response
    {
        $product = $this->productRepository->find($id);
        if (empty($product)) {
            throw $this->createNotFoundException('El producto no existe');
        }
        $cart = $request->getSession()->get('cart', []);
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $cart[$id]['quantity'] + 1;
        } else {
            $cart[$id]['quantity'] = 1;
            $cart[$id]['price'] = $product->getPrice();
        }
        $request->getSession()->set('cart', $cart);

        return $this->redirectToRoute('cart');
    }

    #[Route('/vaciar-carrito', name: 'empty_cart', methods: ['GET'])]
    public function emptyCart(Request $request): Response
    {
        $request->getSession()->remove('cart');

        return $this->redirectToRoute('cart');
    }
}
