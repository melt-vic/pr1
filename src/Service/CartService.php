<?php

namespace App\Service;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private readonly RequestStack $requestStack,
    ) {
    }

    public function addToCart(int $id, Product $product): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart[$id])) {
            ++$cart[$id]['quantity'];
        } else {
            $cart[$id]['quantity'] = 1;
            $cart[$id]['price'] = $product->getPrice();
            $cart[$id]['name'] = $product->getName();
            $cart[$id]['imgPath'] = $product->getImgPath();
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function removeFromCart(int $id): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart[$id])) {
            --$cart[$id]['quantity'];
            if ($cart[$id]['quantity'] <= 0) {
                unset($cart[$id]);
            }
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function emptyCart(): void
    {
        $this->requestStack->getSession()->remove('cart');
    }

    public function calculateTotalAmount(): int
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        return $totalAmount;
    }
}
