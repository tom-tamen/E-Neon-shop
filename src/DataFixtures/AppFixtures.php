<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }


    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user->setEmail('u' . $i . '@mail.com');
            $user->setFirstName('Fname ' . $i);
            $user->setLastName('Lname ' . $i);
            $user->setPassword($this->passwordHasher->hashPassword($user, '123'));
            $user->setRoles(['ROLE_USER']);
            if($i === 0) $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);

            $manager->persist($user);
        }

        $manager->flush();
        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $category->setPictureUrl('nopic.png');

            for($j = 0; $j < 20; $j++) {
                $product = new Product();
                $product->setName('Product ' . $j);
                $product->setPrice(rand(1, 500)+(rand(0, 10) / 10));
                $product->setStock(rand(10, 100));
                $product->setCategory($category);
                $product->setPictureUrl('nopic.png');
                $product->setDescription('Product ' . $j .' from category '. $i .' Cupcake ipsum dolor sit amet candy canes icing candy canes tart. Brownie cupcake I love tart sugar plum. Caramels carrot cake jelly-o gingerbread sweet chocolate cake I love biscuit macaroon.');
                $manager->persist($product);
            }

            $manager->persist($category);
        }
        $manager->flush();

        $product = $manager->getRepository(Product::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        $order = new Order();
        $order->setBuyer($users[0]);
        $order->setDate(new \DateTime());

        for ($i = 0; $i < 10; $i++) {
            $orderProduct = new OrderProduct();
            $orderProduct->setOrderReference($order);
            $orderProduct->setQuantity(rand(1, 10));
            $orderProduct->setProduct($product[rand(0, count($product)-1)]);
            $manager->persist($orderProduct);
        }
        $manager->persist($order);
        $manager->flush();


    }
}
