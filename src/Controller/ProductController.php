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
     * @Route("/", name="home")
     */
    public function home()
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findMoreExpensive();

        return $this->render('product/home.html.twig', [
            'products' =>$products,
        ]);
    }

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
        $product3 = $productRepository->findOneByName('iPhone 12');

        //Récupérer tous les produits qui coutent 1500euros exactement
        $products1500 = $productRepository->findByPrice(1500); 

        //Récupérer le produit le plus cher
        $productExpensive =
        $productRepository->findOneGreaterThanPrice(1400);

        return $this->render('product/demo.html.twig', [
            'products'=>$products,
            'product2'=>$product2,
            'product3'=>$product3,
            'products1500'=>$products1500,
            'product_expensive'=> $productExpensive,
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

    /**
     * @Route("/product/edit/{id}", name="product_edit")
     *
     */
    public function edit (Request $request, Product $product)
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        //Quand le formulaire est valide
        if ($form -> isSubmitted() && $form->isValid()) {
            //On récupére Doctrine pour gérer la BDD
            $entityManager = $this->getDoctrine()->getManager();
            //Execute la requete (UPDATE...)
            $entityManager->flush();
        }

        $this->addFlash('success', 'le produit a été modifié.');

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="product_delete")
     */
    public function delete(Product $product)
    {
        //On récupère Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($product);
        //On éxecute la requete (DELETE)
        $entityManager->flush();

        $this->addFlash('success', 'le produit a été supprimé.');
        //Redirection
        return $this>redirectToRoute('product_list');
    }
}
