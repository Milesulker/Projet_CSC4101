<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Librairie;

class LibrairieController extends AbstractController
{
    #[Route('/librairie', name: 'app_librairie')]
    public function index(ManagerRegistry $doctrine): Response
    {   
        $htmlpage = '<!DOCTYPE html>
            <html>
            <body>
            <h1> Librairies créées </h1>
            Liste des librairies :
            <ul>';
            $entityManager= $doctrine->getManager();
            $librairies = $entityManager->getRepository(Librairie::class)->findAll();
            foreach($librairies as $librairie) {
                $url = $this->generateUrl(
                'librairie_show',
                ['id' => $librairie->getId()]);
                $htmlpage .= '<li>
                <a href="'. $url .'">'. $librairie->getNom() .'</a></li>';
            }
            $htmlpage .= '</ul>
            </body>
            </html>';
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
            );
    }
    
    /**
     * Show a [inventaire]
     *
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/librairie/{id}', name: 'librairie_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id)
    {
        $LibrairieRepo = $doctrine->getRepository(Librairie::class);
        $Librairie = $LibrairieRepo->find($id);
        
        if (!$Librairie) {
            throw $this->createNotFoundException('The Librairie does not exist');
        }
        
        $res = 'Ceci est la ' . $Librairie->getNom() . '<br>';
        $res .= "Elle est d'ID " . $Librairie->getId() .'<br>';
        $res .= '<p/><a href="' . $this->generateUrl('app_librairie') . '">Back</a>';
        
        return new Response('<html><body>'. $res . '</body></html>');
    }
    
}
