<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livre;
use App\Entity\Librairie;

class AppFixtures extends Fixture
{
    private const MAEL_LIB_1 = "mael-inventaire-1";
    private const AYMERIC_LIB_1 = "aymeric-inventaire-1";
    
    private static function getLivresData()
    {
        yield ["La Désolation d'Ignir", 5, "Moyen", self::MAEL_LIB_1];
        yield ["Balade dans la forêt", 1, "Facile", self::AYMERIC_LIB_1];
        yield ["Balade en enfer", 10, "Difficile", self::MAEL_LIB_1];
        
    }
    
    private static function getLibrairiesData()
    {
        yield ["Librairie de Mael", self::MAEL_LIB_1];
        yield ["Librairie d'Aymeric", self::AYMERIC_LIB_1];
        
    }
    
    public function load(ObjectManager $manager)
    {        
        foreach (self::getLibrairiesData() as [$title, $librairieReference]) {
            $librairie = new Librairie();
            $librairie->setNom($title);
            $manager->persist($librairie);
            $manager->flush();
            $this->addReference($librairieReference, $librairie);
        }
        
        foreach (self::getLivresData() as [$title, $level, $desc, $librairieReference]) {
            $librairie = $this->getReference($librairieReference);
            $livre = new Livre();
            $livre->setTitre($title);
            $livre->setNiveau($level);
            $livre->setDescription($desc);
            $librairie->addLivre($livre);
            $manager->persist($librairie);
        }
        $manager->flush();
    }   
}
