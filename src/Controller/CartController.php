<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\OrderDetails;
use App\Entity\Product;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{


    
    // #[Route('/cart', name: 'app_cart_index')]
    // public function index(): Response
    // {
    //     return $this->render('cart/index.html.twig', [
    //         'cartItems' => [],
    //         'cartTotal' => 100,
    //     ]);
    // }


    #[Route('/cart', name: 'app_cart_index')]
public function index(Request $request): Response
{
    $session = $request->getSession();
    $cartItems = $session->get('cart', []);

    // Calculer le montant total du panier
    $cartTotal = 0;
    foreach ($cartItems['price'] as $price) {
        $cartTotal += $price;
    }

    return $this->render('cart/index.html.twig', [
        'cartItems' => $cartItems,
        'cartTotal' => $cartTotal,
    ]);
}

    #[Route('/cart/{idProduct}', name: 'app_cart_add')]
    public function addProduct(Request $request, ProductRepository $productRepository, int $idProduct): Response
    {

        // créer la session
        $session = $request->getSession();
        // $cart = $session->get('cart', []); // permet de videt la session et d'en relancer une

        //si la session existe pas je la crée
        if(!$session->get('cart')) {
            $session->set('cart', [
                "id" => [],
                "title" => [],
                "description" => [],
                "picture" => [],
                "price" => [],
                "stock" => [],
            ]);
        }

        $cart = $session->get('cart');

        // ajouter le produit au panier
        // recupérer les infos du produit en BDD et l'ajouter a mon panier
        $product = $productRepository->find($idProduct);
        $cart["id"][] = $product->getId();
        $cart["title"][] = $product->getTitle();
        $cart["description"][] = $product->getDescription();
        $cart["picture"][] = $product->getPicture();
        $cart["price"][] = $product->getPrice();
        $cart["stock"][] = $product->getStock();

        $session->set('cart', $cart);

        
        // calculer le montant total de mon panier
        $cartTotal = 0;

        for($i = 0; $i < count($session->get('cart')['id']); $i++) {
            $cartTotal += floatval($session->get('cart')["price"]) * $session->get('cart')["stock"][$i];
        }

        // afficher la page panier

        return $this->render('cart/index.html.twig', [
            'cartItems' => $session->get('cart'),
            'cartTotal' => $cartTotal,
        ]);
    }


    /**
     * @Route("/add-to-cart/{id}", name="add_to_cart")
     */

    //  #[Route('/order/cart', name: 'add_to_cart', methods: ['GET', 'POST'])]

    // public function addToCart(Request $request, EntityManagerInterface $entityManager, Product $product): Response
    // {
    //     // Créer un nouvel élément de commande (order detail) pour ce produit
    //     $orderDetail = new OrderDetails();
    //     $orderDetail->setProduct($product);

    //     // Associer cet élément de commande à l'ordre en cours (simulé ici)
    //     // Vous devrez adapter cette partie en fonction de votre modèle de données
    //     $order = $this->getCurrentOrder();
    //     $order->addOrderDetail($orderDetail);

    //     // Enregistrer les modifications dans la base de données
    //     $entityManager->persist($order);
    //     $entityManager->flush();

    //     // Rediriger vers la page du panier ou une autre page
    //     return $this->redirectToRoute('cart_show');
    // }

    // // Méthode de simulation pour récupérer l'ordre en cours
    // private function getCurrentOrder()
    // {
    //     // Vous devrez implémenter cette méthode pour récupérer l'ordre en cours
    //     // pour l'utilisateur actuellement connecté ou pour l'utilisateur anonyme
    //     // Selon votre cas d'utilisation
    // }
}