<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        //Créer les utilisateurs
        $users = [];//Tableau qui va nous aider à stocker les instances des user 
        for ($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setUsername('Username'.$i);
            $manager->persist($user);
            $users[] = $user;//on met l'instance de coté
        }

        for ($i = 0; $i < 50; $i++){
            $product = new Product();
            $product->setName('Iphone XR'.$i);
            $product->setDescription('un telephone'.$i);
            $product->setPrice(rand(500, 1500));
            //On associe le produit à une instance de user($user correspond au drnier user crée)
            $product->setUser($users[rand(0, 9)]);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
