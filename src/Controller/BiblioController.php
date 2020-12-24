<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BiblioController extends AbstractController
{
    /**
     * @Route("/biblio", name="biblio")
     */
    public function index(): Response
    {
        return $this->render('biblio/index.html.twig', [
            'controller_name' => 'BiblioController',
        ]);
    }

    /**
     * @Route("/biblio/emprunts-en-cours", name="biblio_emprunts")
     */
    public function empruntsEnCours(LivreRepository $lr)
    {
        return $this->render("biblio/emprunts.html.twig", [ "livres" => $lr->livresNonRendus() ]);
    }

}

