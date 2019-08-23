<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/create", name="product_create")
     */
    public function create(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //On récupére Doctrine pour gérer la BDD
            $entityManager = $this->getDoctrine()->getManager();
            //On met en attente l'objet dans doctrine
            $entityManager->persist($product);
            //Execute la requete(INSERT...)
            $entityManager->flush();
        }

        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/demo")
     */
    public function demo()
    {
        //Récuperer le repository de l'entité Product
        $productRepository = $this->getDoctrine()->getRepository(Product::class);

        //Récupérer tous les produits
        $products = $productRepository->findAll();

        //Réccupérer le produit 2
        $product2 = $productRepository->find(2);

        //Récuperer le produit qui se nomme iphone X
        $product3 = $productRepository->findOneByName('iPhone X');

        //Récupérer tous les produits qui coutent 1500euros exactement
        $products1500 = $productRepository->findByPrice(1500); 

        return $this->render('product/demo.html.twig', [
            'products'=>$products,
            'product2'=>$product2,
            'product3'=>$product3,
            'products1500'=>$products1500,
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Product $product) //Dans la version commentée il faudrait mettre $id à la place de Product $product
    {
        /*$product = $this->getDoctrine()
            ->getRepository(Product::class)
            ->find($id);

        //Si le produit n'existepas, on renvoie une 404
        if(!$product){
            throw $this->createNotFoundException();
        } */

        return $this->render('product/show.html.twig', [
            'product' =>$product,
        ]);
    }

    /**
     * @Route("/product")
     */
    public function list()
    {
        //Récuperer le repository de l'entité Product
        $productRepository = $this->getDoctrine()->getRepository(Product::class);

        //Récupérer tous les produits
        $products = $productRepository->findAll();

        return $this->render('product/list.html.twig', [
            'products' =>$products,
        ]);
    }
}
