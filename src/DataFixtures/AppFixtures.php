<?php

namespace App\DataFixtures;

use App\Factory\EmployeeFactory;
use App\Factory\ProjectFactory;
use App\Factory\TaskFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        EmployeeFactory::createMany(30);
        ProjectFactory::createMany(5);
        TaskFactory::createMany(15);
        $manager->flush();
    }
}
