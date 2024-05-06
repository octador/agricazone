<?php

namespace App\Controller;

 // Assurez-vous d'importer la classe User si ce n'est pas déjà fait

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    #[Route('/', name: 'home')]

    public function index(Security $security, ProductRepository $productRepository, ?string $category,CategoryRepository $categoryRepository,StockRepository $stockRepository): Response
    {
        $user = $this->getUser();
      
        /**
         * @var User $user
         */
        if($user && !$user->isVerified()) {
            $security->logout(false);
            return $this->redirectToRoute('verification_page');
        }

        $categories = $categoryRepository->findAll();

      
       

        return $this->render('home/index.html.twig', [
            'categories' => $categories
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

    #[Route('/products/{category?}', name: 'app_category_product')]

    public function category(ProductRepository $productRepository, ?string $category,CategoryRepository $categoryRepository,StockRepository $stockRepository): Response
    {
        $user = $this->getUser();
      
        /**
         * @var User $user
         */
     

        $categories = $categoryRepository->findAll();

        
        $products = null;
        if($category) {
            $findCategory = $categoryRepository->findOneBy([
                'name' => $category
            ]);
            $products = $productRepository->findBy([
                'category' => $findCategory->getId()
            ]);

        } 

      
        
       

        return $this->render('home/category.html.twig', [
            'products' => $products, 
            'categories' => $categories
        ]);
    }

}
