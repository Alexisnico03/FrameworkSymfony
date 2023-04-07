<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin', name: 'private_')]
class PrivateController extends AbstractController
{
    #[Route('/private', name: 'app_private')]
    public function index(): Response
    {
        return $this->render('private/index.html.twig', [
            'controller_name' => 'PrivateController',
        ]);
    }

    #[Route('/login', name: 'login_')]
    public function login(): Response
    {
        return $this->render('private/login.html.twig', [
            'controller_name' => 'PrivateController',
        ]);
    }

    #[Route('/listing', name: 'listing_')]
    public function listing(): Response
    {
        return $this->render('private/listing.html.twig', [
            'controller_name' => 'PrivateController',
        ]);
    }
}
