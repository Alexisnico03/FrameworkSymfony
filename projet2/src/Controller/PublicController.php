<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ObjetRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PublicController extends AbstractController

{

    private Security $security;

    private ObjetRepository $objetRepository;

    public function __construct(ObjetRepository $objetRepository , Security $security ) {
        $this->objetRepository = $objetRepository;
        $this->security = $security;
        //parent ::__construct();
    }



    #[Route('/public', name: 'app_public')]
    public function index(): Response
    {

        $listeObjet = $this->objetRepository->findAll();
        return $this->render('public/index.html.twig', [
            'controller_name' => 'PublicController',
            'liste' => $listeObjet
        ]);
    }
    #[Route('/produit/{id}', name: 'produit_')]
    public function produit($id): Response
    {

        $produit = $this->objetRepository->find(['id'=>$id]);
        return $this->render('public/produit.html.twig', [
            'controller_name' => 'PublicController',
            'produit' => $produit
        ]);
    }

    #[Route('/commande', name: 'commande_')]
    public function commande(): Response
    {
        return $this->render('public/commande.html.twig', [
            'controller_name' => 'PublicController',
        ]);
    }

    #[Route('/login/check', name: 'app_check')]

    public function check(): Response {

        $user = $this->security->getUser();

        return $this->redirectToRoute('/');
    }


    #[Route('/login/form', name: 'app_login')]

    public function login(AuthenticationUtils $authenticationUtils): Response {

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('public/login.html.twig', [

            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }


    #[Route('/logout', name: 'app_logout')]

    public function logout(): Response {
        
        $this->security->logout();

        return new RedirectResponse('/');
    }


}
