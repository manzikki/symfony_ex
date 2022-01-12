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
