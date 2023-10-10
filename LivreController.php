<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Livre;

class LivreController extends AbstractController
{
    #[Route('/livre/{id}', name: 'app_livre', requirements: ['id' => '\d+'])]
    public function index(ManagerRegistry $doctrine, $id)
    {
        $LivreRepo = $doctrine->getRepository(Livre::class);
        $Livre = $LivreRepo->find($id);
        
        if (!$Livre) {
            throw $this->createNotFoundException('The Livre does not exist');
        }
        return $this->render('livre/index.html.twig',
            [ 'livre' => $Livre ]
            );
    }
}
