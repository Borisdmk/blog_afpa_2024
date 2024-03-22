<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentController extends AbstractController
{
    #[Route('/payment', name: 'pay')]
    public function index(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager, OrderRepository $orderRepository): Response
    {

        // je récupere la session cart;
        $cart = $request->getSession()->get('cart');
        
        // je crée une commande:
        $order = new Order;

        $cartTotal = 0;

        for($i = 0; $i < count($cart["id"]); $i++) {
            $cartTotal += (float) $cart["price"][$i] * $cart["stock"][$i];
        }


        // je set le montant 
        $order->setTotal($cartTotal);

        // je set le statut
        $order->setStatus('en cours');

        // je set le user
        $order->setUser($this->getUser());

        // je set la date
        $order->setDate(new \DateTime);


        // pour chaque élément de mon panier je crée un détail de commande
        for($i = 0; $i < count($cart["id"]); $i++) {
            $orderDetails= new OrderDetails;
            $orderDetails->setIdOrder($orderRepository->findOnBy([], ['id' => 'DESC']));
            $orderDetails->setProduct($productRepository->find($cart["id"][$i]));
            $orderDetails->setQuantity($cart["id"][$i]);

            $entityManager->persist($orderDetails);
            $entityManager->flush();
        }

        return $this->render('payment/index.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
}
