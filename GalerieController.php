<?php

namespace App\Controller;

use App\Entity\Galerie;
use App\Entity\Livre;
use App\Form\GalerieType;
use App\Repository\GalerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

#[Route('/galerie')]
class GalerieController extends AbstractController
{
    #[Route('/', name: 'app_galerie_index', methods: ['GET'])]
    public function index(GalerieRepository $galerieRepository): Response
    {
        return $this->render('galerie/index.html.twig', [
            'galeries' => $galerieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_galerie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $galerie = new Galerie();
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($galerie);
            $entityManager->flush();

            return $this->redirectToRoute('app_galerie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('galerie/new.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_galerie_show', methods: ['GET'])]
    public function show(Galerie $galerie): Response
    {
        return $this->render('galerie/show.html.twig', [
            'galerie' => $galerie,
        ]);
    }
    
    #[Route('/{galerie_id}/livre/{livre_id}', methods: ['GET'], name: 'app_galerie_show_livre')]
    public function livreShow(
        #[MapEntity(id: 'galerie_id')]
        Galerie $galerie,
        #[MapEntity(id: 'livre_id')]
        Livre $livre
        ): Response
        {
            if(! $galerie->getObjets()->contains($livre)) {
                throw $this->createNotFoundException("Ce livre n'est pas dans cette galerie !");
            }
            
            if(! $galerie->isPublic()) {
                throw $this->createAccessDeniedException("Accès non autorisé");
            }
            return $this->render('galerie/livre.show.html.twig', [
                'livre' => $livre,
                'galerie' => $galerie
            ]);
    }

    #[Route('/edit/{id}', name: 'app_galerie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Galerie $galerie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GalerieType::class, $galerie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_galerie_show', ['id' => $galerie->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('galerie/edit.html.twig', [
            'galerie' => $galerie,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_galerie_delete', methods: ['POST'])]
    public function delete(Request $request, Galerie $galerie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$galerie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($galerie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_galerie_index', [], Response::HTTP_SEE_OTHER);
    }
}
