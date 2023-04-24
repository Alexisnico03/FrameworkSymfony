<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ObjetRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Persistence\ManagerRegistry;

class PublicController extends AbstractController

{

    private Security $security;

    private ObjetRepository $objetRepository;

    private ManagerRegistry $doctrine;

    public function __construct(ObjetRepository $objetRepository , Security $security, ManagerRegistry $doctrine ) {
        $this->objetRepository = $objetRepository;
        $this->security = $security;
        $this->doctrine = $doctrine;
        //parent ::__construct();
    }



    #[Route('/', name: 'app_public')]
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

    #[Route('/commande', name: 'commande')]
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



    #[Route("/user/create", name:"user_create")]
    public function createUserAction(Request $request): Response
    {
    $user = new user(); // ATTENTION mettre VOTRE classe user
    $form = $this->createFormBuilder($user)
            ->add('Ville', TextType::class) // On ajoute les champs adresse, cp, ville, etc…
            ->add('Code_postal', TextType::class)
    ->add('Adresse', TextType::class)
    ->add('prenom', TextType::class)
    ->add('nom', TextType::class)
    ->add('login', TextType::class)
    ->add('MDP', TextType::class)
        ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
            ->getForm();

            //A ajouter avant le render
            $form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) {
    $user = $form->getData(); //On récupère l’objet
    $user->setRoles(['ROLE_USER']); //On force l’utilisateur à être un ROLE_USER normal
    $em = $this->doctrine->getManager();    
            try {
    $em->persist($user); //La Sauvegarde
    $em->flush();  	 
    return new RedirectResponse('/');
            } catch (UniqueConstraintViolationException $e){
                  $form->get('login')->addError(new FormError('Identifiant déjà utilisé')); //Mettre le bon champ identifiant
            }
    }
    
    
    return $this->render('public/createUser.html.twig', array(
                'form' => $form->createView(), // on le passe au template
    
    ));
    }
    


}
