<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    public function navbar(CategoryRepository $categoryRepository): Response
    {
        return $this->render('menu/navbar.html.twig', [
            'categories' => $categoryRepository->findBy([],[], 4),
        ]);
    }
}
