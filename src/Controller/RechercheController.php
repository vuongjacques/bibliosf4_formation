<?php

namespace App\Controller;

use App\Repository\EmpruntRepository;
use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RechercheController extends AbstractController
{
    /**
     * @Route("/recherche", name="recherche")
     */
    public function index(Request $rq, LivreRepository $lr): Response
    {
        $motRecherche = $rq->query->get("mot");
        $livres = $lr->rechercheParTitre($motRecherche);
        return $this->render('recherche/index.html.twig', [ "mot" => $motRecherche, "livres" => $livres ]);
    }

    /**
     * @Route("/recherche/livres-indisponibles", name="recherche_livres_indispo")
     */
    public function livresIndisponibles(LivreRepository $lr)
    {
        return $this->render("recherche/livres_indisponibles.html.twig", [ "livres" => $lr->livresNonRendus() ]);
    }

    /**
     * @Route("/recherche/livres-indisponibles", name="recherche_indispo")
     */
    public function indisponibles(EmpruntRepository $em)
    {
        /* 1. Récupérer les emprunts dont la date retour est nulle:
                SELECT * FROM emprunt e WHERE e.date_retour IS NULL
            2. Affichez la liste sous forme de vignette de lire. Sur la page sera affichée aussi : "Liste des livres non disponibles"
        */
        $emprunts = $em->livresIndispo();
        return $this->render("recherche/indisponibles.html.twig", [ "emprunts" => $emprunts ]);
    }


}
