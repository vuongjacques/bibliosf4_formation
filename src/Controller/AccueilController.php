<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index( LivreRepository $lr ): Response
    {
        $tousLesLivres = $lr->findAll();
        $livresNonRendus = $lr->livresNonRendus();
        $livresDisponibles = $lr->livresDisponibles();
        
        
        // $livresDisponibles = [];
        // foreach($tousLesLivres as $livre){
        //     if( !in_array($livre, $livresNonRendus) ){
        //         $livresDisponibles[] = $livre;
        //     }
        // }

        /* EXO: modifier l'affichage pour afficher d'abord les livres disponibles (avec le bouton reserver si l'abonné est connecté avec le ROLE_ABONNE) et ensuite les livres non disponibles*/
        return $this->render('accueil/index.html.twig', [ "livresNonRendus" => $livresNonRendus, "livresDisponibles" => $livresDisponibles ]);
    }
}
