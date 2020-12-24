<?php

namespace App\Controller;

use App\Entity\Emprunt;
use App\Entity\Livre;
use App\Repository\LivreRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LecteurController extends AbstractController
{
    /**
     * @Route("/lecteur", name="lecteur")
     */
    public function index(): Response
    {
        $empruntsAbonneConnecte = $this->getUser()->getEmprunts();
        
        return $this->render('lecteur/index.html.twig');
    }

    /**
     * @Route("/lecteur/reserver/{id}", name="reserver_livre", requirements={"id"="[0-9]+"})
     */
    public function reserver(Livre $livreAemprunter, EntityManagerInterface $em, LivreRepository $lr)
    {
        /*EXO : Créer un nouvel emprunt après avoir vérifié que l'utilisateur connecté a le role Abonné et que le livre est disponible. La date d'emprunt sera la date d'aujourd'hui
        Enregistrer l'emprunt en BDD et rediriger vers la route "lecteur" avec un message qui confirme l'emprunt (en rappelant le titre du livre emprunté)
        Changer les liens sur les vignettes livres pour le bouton 'reserver'
         */
        // $livre = $lr->find($id);

        $livresDispo = $lr->livresDisponibles();
        if( in_array($livreAemprunter, $livresDispo) ){
            $nouvelEmprunt = new Emprunt;
            $nouvelEmprunt->setLivre($livreAemprunter);

            $abonneConnecte = $this->getUser();
            $nouvelEmprunt->setAbonne($abonneConnecte);

            $auj = new DateTime("now");
            $nouvelEmprunt->setDateEmprunt($auj);

            $em->persist($nouvelEmprunt);
            $em->flush();
            $this->addFlash("success", "Votre emprunt du livre " . $livreAemprunter->getTitre() . " a été enregistré");
            return $this->redirectToRoute("lecteur");  
        } else {
            $this->addFlash("warning", "Désolé, ce livre n'est plus disponible");
            return $this->redirectToRoute("accueil");
        }           
    }
}
