<?php

namespace App\Controller;

 // Assurez-vous d'importer la classe User si ce n'est pas déjà fait

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Security $security, ProductRepository $productRepository): Response
    {
        $user = $this->getUser();

        /**
         * @var User $user
         */
        if($user && !$user->isVerified()) {
            $security->logout(false);
            return $this->redirectToRoute('verification_page');
        }
        
        $fullProducts = $productRepository->findAll();
        
        return $this->render('home/index.html.twig', [
            'fullProducts' => $fullProducts
        ]);
    }

    #[Route('/verification', name: 'verification_page')]
    public function verificationPage(): Response
    {
        $this->addFlash(
            'warning',
            'Veillez à vérifier votre compte avec votre boîte mail'
        );
        return $this->render('security/verif-mail.html.twig');
    }

}
