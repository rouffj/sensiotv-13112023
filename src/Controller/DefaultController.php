<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function homepage(): Response
    {
        return $this->render('default/homepage.html.twig');
    }

    #[Route('/default', name: 'app_default')]
    public function index(): Response
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[
        Route(
            '/contact/{reasonId}',
            requirements: ['reasonId' => '\d+'],
            defaults: ['reasonId' => 17],
            methods: 'GET',
            name: 'titi'
        )
    ]
    public function contact($reasonId)
    {
        //dump($reasonId);die;
        //$imdbClient->list();

        return new Response('<h1>Page</h1> contact');
    }

    #[Route('/movies/{id}')]
    public function movies_list($id, Request $request)
    {
        dump($id, $request);
        die;
    }
}

// -----

/*
class User
{
    private $firstName;

    private $lastName;

    public function getFirstName()
    {
        return $this->firstName;
    }
}

$joseph = new User;
$joseph->getFirstName();
$joseph->lastName;
*/
