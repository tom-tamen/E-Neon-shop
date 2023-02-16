<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart_show')]
    public function index(Request $request, ProductRepository $productRepository): Response
    {
        $session = $request->getSession();
        $cart = $session->get('cart', []);

        $products = [];
        foreach ($cart as $id => $quantity) {

            $check = $productRepository->find($id);

            if(!$check){
                unset($cart[$id]);
                $session->set('cart', $cart);
            }else{
                $products[] = [
                    'product' => $check,
                    'quantity' => $quantity
                ];
            }
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $products,
        ]);
    }

    #[Route('/cart/add/{id}', name: 'cart_add')]
    #[Route('/cart/add-x/{id}/{x}', name: 'cart_add_x')]
    public function add($id, Request $request, ProductRepository $productRepository, $x = null){

        $session = $request->getSession();
        $cart = $session->get('cart', []);

        $product = $productRepository->find($id);

        if (!empty($cart[$id]) || !$product) {

            if($product->getStock() < $cart[$id] + $x){
                $cart[$id] = $product->getStock();
                $session->set('cart', $cart);
                return $this->redirectToRoute('product_show', ['id' => $id, 'error' => 'stock']);
            }

            if($x === null){
                $cart[$id]++;
            }else{
                $cart[$id] += $x;
            }
        } else {
            $cart[$id] = 1;
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/cart/remove-all/{id}', name: 'cart_remove_all')]
    #[Route('/cart/remove/{id}/{x}', name: 'cart_remove_x')]
    public function remove($id, Request $request, $x = null){

        $session = $request->getSession();
        $cart = $session->get('cart', []);

        if(!isset($cart[$id])){
            return $this->redirectToRoute('app_index');
        }

        if($x === null){
            unset($cart[$id]);
        }else{

            $cart[$id] -= $x;

            if($cart[$id] <= 0){

                unset($cart[$id]);

            }
        }


        $session->set('cart', $cart);

        return $this->redirectToRoute('cart_show');
    }

    #[Route('/cart/dump', name: 'cart_dump')]
    public function dump(Request $request){

        $session = $request->getSession();
        $cart = $session->get('cart', []);
        dd($cart);
    }
}
