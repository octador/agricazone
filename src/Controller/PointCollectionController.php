<?php

namespace App\Controller;

use App\Entity\PointCollection;
use App\Form\PointCollectionType;
use App\Repository\PointCollectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/point/collection')]
class PointCollectionController extends AbstractController
{
    #[Route('/', name: 'app_point_collection_index', methods: ['GET'])]
    public function index(PointCollectionRepository $pointCollectionRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $pointCollection = new PointCollection();
        $form = $this->createForm(PointCollectionType::class, $pointCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pointCollection);
            $entityManager->flush();

            return $this->redirectToRoute('app_point_collection_index', [], Response::HTTP_SEE_OTHER);
        }


        return $this->render('point_collection/index.html.twig', [
            'point_collections' => $pointCollectionRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_point_collection_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $pointCollection = new PointCollection();
        $form = $this->createForm(PointCollectionType::class, $pointCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($pointCollection);
            $entityManager->flush();

            return $this->redirectToRoute('app_point_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('point_collection/new.html.twig', [
            'point_collection' => $pointCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_point_collection_show', methods: ['GET'])]
    public function show(PointCollection $pointCollection): Response
    {
        return $this->render('point_collection/show.html.twig', [
            'point_collection' => $pointCollection,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_point_collection_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PointCollection $pointCollection, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PointCollectionType::class, $pointCollection);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_point_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('point_collection/edit.html.twig', [
            'point_collection' => $pointCollection,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_point_collection_delete', methods: ['POST'])]
    public function delete(Request $request, PointCollection $pointCollection, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$pointCollection->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($pointCollection);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_point_collection_index', [], Response::HTTP_SEE_OTHER);
    }
}
