<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category/{id}', name: 'category_show')]
    #[Route('/category/{id}/{filter}', name: 'category_show_filter')]
    public function index($id, CategoryRepository $categoryRepository, ProductRepository $productRepository,$filter = null): Response
    {
        $category = $categoryRepository->find($id);
        if(!$category) {
            throw $this->createNotFoundException('Category not found');
        }

        if ($filter == 'asc'){
            $products = $productRepository->getSorted($category, 'ASC');
        }elseif ($filter == 'desc'){
            $products = $productRepository->getSorted($category, 'DESC');
        }else{
            $products = $category->getProducts();
        }
        return $this->render('category/index.html.twig', [
            'category' => $category,
            'products' => $products
        ]);
    }
}
