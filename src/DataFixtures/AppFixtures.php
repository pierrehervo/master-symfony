<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    //On récupere le service pour encoder les mots de ppasse dans symfony
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //Creer customerADMIN
        $customer = new Customer();
        $customer->setEmail('pierre.hervo@laposte.net');
        $customer->setRoles(['ROLE_ADMIN']);
        $customer->setFirstName('Pierrolrigolo');
        //On genere le hash du mdp test
        $encodedPassword = $this->passwordEncoder->encodePassword($customer, 'test');
        $customer->setPassword($encodedPassword);
        $manager->persist($customer);

        //Créer les utilisateurs
        $users = [];//Tableau qui va nous aider à stocker les instances des user 
        for ($i = 0; $i < 10; $i++){
            $user = new User();
            $user->setUsername('Username'.$i);
            $manager->persist($user);
            $users[] = $user;//on met l'instance de coté
        }

        //Créer les categories
        $categories = [];//Tableau qui va nous aider à stocker les instances des user 
        for ($i = 0; $i < 10; $i++){
            $category = new Category();
            $category->setname('Category'.$i);
            $manager->persist($category);
            $categories[] = $category;//on met l'instance de coté
        }

        //Créer les tag
        for ($i = 0; $i < 10; $i++){
            $tag = new Tag();
            $tag->setname('Tag'.$i);
            $manager->persist($tag);
        }

        for ($i = 0; $i < 50; $i++){
            $product = new Product();
            $product->setName('Iphone XR'.$i);
            $product->setDescription('un telephone'.$i);
            $product->setPrice(rand(500, 1500));
            //On associe le produit à une instance de user($user correspond au drnier user crée)
            $product->setUser($users[rand(0, 9)]);
            $product->setCategory($categories[rand(0, 9)]);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
