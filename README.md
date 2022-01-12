# symfony_ex
A testing ground for Symfony tricks

1. Building a skeleton of an application and testing it

composer create-project symfony/website-skeleton dbapp
cd dbapp/public
php -S 0.0.0.0:8000


2. Connecting to mysql

Install mysql, create a demo database with a user/password.
Example:

mysql
create database demo;
create user demo identified by "demo";
grant all on demo.* to demo;

Modify the file .env in the dbapp folder
DATABASE_URL="mysql://demo:demo@127.0.0.1:3306/demo"

php bin/console doctrine:database:create
(fails because the db exists but that's ok)

3. Creating entities. In this example we have persons who own books (one-to-many relationship)
php bin/console make:entity

[create entity Person with an attribute called name]
[create entity Book with an attribute called name]

php bin/console make:migration
php bin/console doctrine:migrations:migrate

4. Creating relations

php bin/console make:entity
Person
[add a new attribute called books, type relation, OneToMany]

5. Creating code for persisting persons

Put the following code in dbapp/src/Controller/PersonController.php

<?php
namespace App\Controller;
use App\Entity\Person; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


class PersonController extends AbstractController
{
    #[Route('/person', name: 'person')]
    public function index(): Response
    {
        return $this->render('person/index.html.twig', [
            'controller_name' => 'PersonController',
        ]);
    }

    /**
     * @Route("/newperson", name="new_person")
     */
    public function newPerson(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $per = new Person();
        $per->setName('John');
        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($per);
        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return new Response('Saved new person with id '.$per->getId());
    }
}

Start the server in public (php -S 0.0.0.0:8000) and with your browser go to http://localhost:8000/newperson
You should get a response "Saved new person with id 1"





