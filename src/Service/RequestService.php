<?php

namespace App\Service;

use App\Entity\Request;
use App\Entity\RequestLine;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;

class RequestService
{
    public function __construct(
        private readonly ProductRepository $productRepository,
        private readonly UserRepository $userRepository,
        private readonly ManagerRegistry $mr,
        private readonly CartService $cartService,
    ) {
    }

    public function insertCart(): void
    {
        $cart = $this->cartService->getCart();
        if (isset($cart['products'])) {
            $em = $this->mr->getManager();
            $request = (new Request())
                ->setUser($this->userRepository->find($cart['user']['id']))
                ->setDateCreated(new \DateTime());
            foreach ($cart['products'] as $productId => $item) {
                $this->addRequestLine($em, $request, $item, $productId);
            }
            $em->persist($request);
            $em->flush();

            $this->cartService->emptyCart();
        }
    }

    private function addRequestLine(EntityManager $em, Request $request, array $item, int $productId): void
    {
        $product = $this->productRepository->find($productId);
        $requestLine = (new RequestLine())
            ->setProduct($product)
            ->setQuantity($item['quantity']);
        $em->persist($requestLine);

        $request->addRequestLine($requestLine);
    }
}
