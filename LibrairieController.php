<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Librairie;
use App\Entity\Membre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LibrairieType;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;

class LibrairieController extends AbstractController
{
    #[Route('', name: 'home')]
    public function accueil(): Response
    {
        return $this->render('welcome.html.twig');
    }
    
    #[Route('/librairie', name: 'app_librairie')]
    public function index(ManagerRegistry $doctrine): Response
    {   
        $entityManager= $doctrine->getManager();
        $librairies = $entityManager->getRepository(Librairie::class)->findAll();
        return $this->render('librairie/index.html.twig',
            [ 'librairies' => $librairies ]
            );
    }
    
    #[Route('/librairie/show/{librairie_id}', name: 'librairie_show', requirements: ['id' => '\d+'])]
    public function show(#[MapEntity(id: 'librairie_id')] Librairie $librairie)
    {
        return $this->render('librairie/show.html.twig',
        [ 'librairie' => $librairie ]
        );
    }
    
    #[Route('/librairie/new', name: 'app_librairie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $librairie = new Librairie();
        
        $form = $this->createForm(LibrairieType::class, $librairie,);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($librairie);
                $entityManager->flush();
                $this->addFlash('message', 'Bien ajouté');
                return $this->redirectToRoute('app_librairie', [], Response::HTTP_SEE_OTHER);
            }
        }
        
        return $this->render('librairie/new.html.twig', [
            'librairie' => $librairie,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/librairie/newinmembre/{id}', name: 'app_librairie_newinmembre', methods: ['GET', 'POST'])]
    public function newInMembre(Request $request, EntityManagerInterface $entityManager, Membre $membre): Response
    {
        $librairie = new Librairie();
        $librairie->setMembre($membre);
        
        $form = $this->createForm(LibrairieType::class, $librairie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($librairie);
                $entityManager->flush();
                $this->addFlash('message', 'Bien ajouté');
                return $this->redirectToRoute('app_membre_show', ['id' => $membre->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        
        return $this->render('librairie/newinmembre.html.twig', [
            'membre' => $membre,
            'librairie' => $librairie,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/librairie/edit/{id}', name: 'app_librairie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Librairie $librairie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LibrairieType::class, $librairie);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('librairie_show', ['id' => $librairie->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('librairie/edit.html.twig', [
            'librairie' => $librairie,
            'form' => $form,
        ]);
    }
    
    #[Route('/librairie/delete/{id}', name: 'app_librairie_delete', methods: ['POST'])]
    public function delete(Request $request, Librairie $librairie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$librairie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($librairie);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('app_librairie', [], Response::HTTP_SEE_OTHER);
    }
    
}
