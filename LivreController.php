<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Livre;
use App\Entity\Librairie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Form\LivreType;

class LivreController extends AbstractController
{
    #[Route('/livre/{id}', name: 'app_livre', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id)
    {
        $LivreRepo = $doctrine->getRepository(Livre::class);
        $Livre = $LivreRepo->find($id);
        
        if (!$Livre) {
            throw $this->createNotFoundException('The Livre does not exist');
        }
        return $this->render('livre/show.html.twig',
            [ 'livre' => $Livre ]
            );
    }
    
    #[Route('/livre/newinlibrairie/{id}', name: 'app_livre_newinlibrairie', methods: ['GET', 'POST'])]
    public function newInLibrary(Request $request, EntityManagerInterface $entityManager, Librairie $librairie): Response
    {
        $livre = new Livre();
        $livre->setLibrairie($librairie);
        
        $form = $this->createForm(LivreType::class, $livre,
            [
                'display_librairie' => false]
            );
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->persist($livre);
                $entityManager->flush();
                $this->addFlash('message', 'Bien ajouté');
                return $this->redirectToRoute('librairie_show', ['librairie_id' => $librairie->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        
        return $this->render('livre/newinlibrairie.html.twig', [
            'librairie' => $librairie,
            'livre' => $livre,
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/livre/edit/{id}', name: 'app_livre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            return $this->redirectToRoute('app_livre', ['id' => $livre->getId()], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('livre/edit.html.twig', [
            'livre' => $livre,
            'form' => $form,
        ]);
    }
    
    #[Route('/livre/delete/{id}', name: 'app_livre_delete', methods: ['POST'])]
    public function delete(Request $request, Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $librairie = $livre->getLibrairie();
        if ($this->isCsrfTokenValid('delete'.$livre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('librairie_show', ['librairie_id' => $librairie->getId()], Response::HTTP_SEE_OTHER);
    }
}
