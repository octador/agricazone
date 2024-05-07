<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Stock;
use App\Entity\User;
use App\Form\StockType;
use App\Form\UserType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ajouter picture
            $pictureFile = $form->get('picture')->getData();
            $this->uploadPicture($user, $pictureFile, $slugger);
        
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/admin/farmer/{id}', name: 'app_user_show_admin_farmer', methods: ['GET', 'POST'])]
    public function showAdmin(User $user,ProductRepository $productRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // recupere tout les products et les afficher
        // mettre un price et les rentrée en base de donnée
        // mettre une description et la rentré en base de donnée
        // ajouter le status
        
        $stock = new Stock();
        $stock->setCreatedAt(new \DateTimeImmutable());
        $stock->setUser($user);

        // dd($product->getPicture());

        $formStock = $this->createForm(StockType::class, $stock);

        $formStock->handleRequest($request);

        if($formStock->isSubmitted() && $formStock->isValid()){
            $productCategory = $request->request->all()['product'];

            $product = $productRepository->findOneBy(['name' => $productCategory]);
            
            // is Available 
            $stock->setProduct($product);

           
            $entityManager->persist($stock);
            $entityManager->flush();

        }
        
        

        
        $listProducts = $productRepository->findAll();
        
        
        return $this->render('user/showAdminFarmer.html.twig', [
            'user' => $user,
            'listProducts' => $listProducts,
            'formStock' => $formStock
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFile = $form->get('picture')->getData(); // get picture from request
            $this->uploadPicture($user, $pictureFile, $slugger);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    private function uploadPicture(User $user, $pictureFile, $slugger): void 
    {
        if(!$pictureFile) {
            return;
        }

            $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$pictureFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $pictureFile->move($this->getParameter('pictures_dir'), $newFilename);
                $user->setPicture($newFilename);
            } catch (FileException $e) {
                dd($e->getMessage());
                // ... handle exception if something happens during file upload
            }

    }
}
