<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/create", name="category_create")
     */
    public function create(Request $request)
    {
        $category = new Category();
        
        return $this->render('category/create.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }
}
