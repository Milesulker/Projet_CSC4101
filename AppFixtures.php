<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livre;
use App\Entity\Librairie;
use App\Entity\Membre;
use App\Entity\Galerie;

class AppFixtures extends Fixture
{
    private const MAEL_LIB_1 = "mael-inventaire-1";
    private const JADE_LIB_1 = "jade-inventaire-1";
    private const AYMERIC_LIB_1 = "aymeric-inventaire-1";
    private const MAEL_MEM_1 = "mael-membre-1";
    private const JADE_MEM_1 = "jade-membre-1";
    private const AYMERIC_MEM_1 = "aymeric-membre-1";
    private const MAEL_LIV_1 = "mael-liv-1";
    private const MAEL_LIV_2 = "mael-liv-2";
    private const JADE_LIV_1 = "jade-liv-1";
    private const AYMERIC_LIV_1 = "aymeric-liv-1";
    private const MAEL_GAL_1 = "mael-gal-1";
    private const JADE_GAL_1 = "jade-gal-1";
    private const AYMERIC_GAL_1 = "aymeric-gal-1";
    
    
    /* [Titre, Niveau, Desc, Réference Librairie] */
    private static function getLivresData()
    {
        yield ["La Désolation d'Ignir", 5, "Aventure très sympathique mais de difficulté moyenne", self::JADE_LIB_1, self::JADE_LIV_1];
        yield ["Balade dans la forêt", 1, "Très bonne aventure pour débuter !", self::AYMERIC_LIB_1, self::AYMERIC_LIV_1];
        yield ["Balade en enfer", 10, "Vous allez mourir, beaucoup", self::MAEL_LIB_1, self::MAEL_LIV_1];
        yield ["Tombe de l'annihilation", 8, "En cours de test", self::MAEL_LIB_1, self::MAEL_LIV_2];
        
    }
    /* [Nom, Réference Librairie, Réference Membre] */
    private static function getLibrairiesData()
    {
        yield ["Librairie du maître", self::MAEL_LIB_1, self::MAEL_MEM_1];
        yield ["Fairy Tail <3", self::JADE_LIB_1, self::JADE_MEM_1];
        yield ["Librairie du débutant", self::AYMERIC_LIB_1, self::AYMERIC_MEM_1];
        
    }
    /* [Nom, Profil, Réference Membre] */
    private static function getMembersData()
    {
        yield ["Mael", "DM depuis tout bébé", self::MAEL_MEM_1];
        yield ["Jade", "ERZA AAAHHHHH", self::JADE_MEM_1];
        yield ["Aymeric", "Nouveau fan !", self::AYMERIC_MEM_1];  
    }
    /* [Desc, Public, Réference Galerie, Réference Membre] */
    private static function getGaleriesData()
    {
        yield ["Mon jardin secret", false, self::MAEL_GAL_1, self::MAEL_MEM_1];
        yield ["Les meilleures 100 Year Quests", true, self::JADE_GAL_1, self::JADE_MEM_1];
        yield ["Mes petites trouvailles", true, self::AYMERIC_GAL_1, self::AYMERIC_MEM_1];
    }
    
    private static function GaleriesData()
    {
        yield [self::MAEL_GAL_1, self::MAEL_LIV_2];
        yield [self::AYMERIC_GAL_1, self::AYMERIC_LIV_1];
        yield [self::JADE_GAL_1, self::JADE_LIV_1];
    }
    
    public function load(ObjectManager $manager)
    {     
       
        
        foreach (self::getMembersData() as [$nom, $profil, $membreReference]) {
           $member = new Membre();
           $member->setNom($nom);
           $member->setProfil($profil);
           $manager->persist($member);
           $manager->flush();
           $this->addReference($membreReference, $member);
        }
        
        foreach (self::getLibrairiesData() as [$title, $librairieReference, $membreReference]) {
            $member = $this->getReference($membreReference);
            $librairie = new Librairie();
            $librairie->setNom($title);
            $member->addLibrairie($librairie);
            $manager->persist($librairie);
            $manager->flush();
            $this->addReference($librairieReference, $librairie);
        }
        
        foreach (self::getLivresData() as [$title, $level, $desc, $librairieReference, $livreReference]) {
            $librairie = $this->getReference($librairieReference);
            $livre = new Livre();
            $livre->setTitre($title);
            $livre->setNiveau($level);
            $livre->setDescription($desc);
            $librairie->addLivre($livre);
            $manager->persist($librairie);
            $this->addReference($livreReference, $livre);
        }
        
        $manager->flush();
        
        foreach (self::getGaleriesData() as [$description, $public, $galerieReference, $membreReference]) {
            $member = $this->getReference($membreReference);
            $galerie = new Galerie();
            $galerie->setDescription($description);
            $galerie->setPublic($public);
            $member->addGalerie($galerie);
            $manager->persist($galerie);
            $manager->flush();
            $this->addReference($galerieReference, $galerie);
        }
        
        foreach (self::GaleriesData() as [$galerieReference, $livreReference]) {
            $galerie = $this->getReference($galerieReference);
            $livre = $this->getReference($livreReference);
            $galerie->addObjet($livre);
            $livre->addGalerie($galerie);
        }
        
        $manager->flush();
    }   
}
