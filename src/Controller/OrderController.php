<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class OrderController extends AbstractController
{

    #[Route('/order', name: 'order_show_all')]
    public function index(Security $security): Response
    {
        $user = $security->getUser();
        return $this->render('order/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/order-validate', name: 'order_validate')]
    public function validate(Security $security, Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $order = new Order();
        $order->setBuyer($user);
        $order->setDate(new \DateTime());
        $order->setTotal(0);
        $entityManager->persist($order);

        $orderTotal = 0;

        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if (empty($cart)) {
            return $this->redirectToRoute('cart_show', ['error' => 'empty']);
        }

        foreach ($cart as $id => $quantity) {
            $product = $productRepository->find($id);
            if(!$product){
                unset($cart[$id]);
                $session->set('cart', $cart);
            }else{
                if ($product->getStock() - $quantity < 0) {
                    return $this->redirectToRoute('cart_show', ['error' => 'stock']);
                }else{
                    $product->setStock($product->getStock() - $quantity);
                    $entityManager->persist($product);
                    $entityManager->flush();

                    $orderProduct = new OrderProduct();
                    $orderProduct->setOrderReference($order);
                    $orderProduct->setQuantity($quantity);
                    $orderProduct->setProduct($product);
                    $orderProduct->setTotal($product->getPrice() * $quantity);
                    $orderTotal += $product->getPrice() * $quantity;
                    $entityManager->persist($orderProduct);

                }
            }
        }
        if(empty($cart)){
            return $this->redirectToRoute('cart_show', ['error' => 'empty']);
        }
        $order->setTotal($orderTotal);
        $entityManager->persist($order);
        $entityManager->flush();
        $session->set('cart', []);
        return $this->redirectToRoute('order_show_all');
    }
}
