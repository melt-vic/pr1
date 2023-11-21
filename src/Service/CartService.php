<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
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
