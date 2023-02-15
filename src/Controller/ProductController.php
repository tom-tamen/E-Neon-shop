<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'product_show')]
    public function index($id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if(!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        return $this->render('product/index.html.twig', [
            'product' => $product
        ]);
    }
}
