<?php

namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(private readonly RequestStack $requestStack)
    {
    }

    public function addToCart(int $id, Product $product): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart['products'][$id])) {
            ++$cart['products'][$id]['quantity'];
        } else {
            $cart['products'][$id]['quantity'] = 1;
            $cart['products'][$id]['price'] = $product->getPrice();
            $cart['products'][$id]['name'] = $product->getName();
            $cart['products'][$id]['imgPath'] = $product->getImgPath();
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function removeFromCart(int $id): void
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        if (isset($cart['products'][$id])) {
            --$cart['products'][$id]['quantity'];
            if ($cart['products'][$id]['quantity'] <= 0) {
                unset($cart['products'][$id]);
            }
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function removeProductFromCart(int $id): void
    {
        $cart = $this->requestStack->getSession()->get('cart');
        if (isset($cart['products'][$id])) {
            unset($cart['products'][$id]);
        }
        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function emptyCart(): void
    {
        $this->requestStack->getSession()->remove('cart');
    }

    public function getNumberOfProducts(): int
    {
        $cart = $this->requestStack->getSession()->get('cart');
        if (!isset($cart['products'])) {
            return 0;
        }

        return array_sum(array_column($cart['products'], 'quantity'));
    }

    public function calculateTotalAmount(): int
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $totalAmount = 0;
        foreach ($cart['products'] as $item) {
            $totalAmount += $item['quantity'] * $item['price'];
        }

        return $totalAmount;
    }

    public function getCart(): ?array
    {
        return $this->requestStack->getSession()->get('cart');
    }

    public function addUserId(User $user): void
    {
        $cart = $this->requestStack->getSession()->get('cart');
        $cart['user']['id'] = $user->getId();
        $cart['user']['name'] = $user->getName();
        $cart['user']['email'] = $user->getEmail();
        $cart['user']['address'] = $user->getAddress();

        $this->requestStack->getSession()->set('cart', $cart);
    }
}
