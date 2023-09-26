<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Livre;
use App\Entity\Librairie;
use App\Entity\Member;

class AppFixtures extends Fixture
{
    private const MAEL_LIB_1 = "mael-inventaire-1";
    private const AYMERIC_LIB_1 = "aymeric-inventaire-1";
    private const MAEL_MEM_1 = "mael-membre-1";
    private const AYMERIC_MEM_1 = "aymeric-membre-1";
    
    private static function getLivresData()
    {
        yield ["La Désolation d'Ignir", 5, "Moyen", self::MAEL_LIB_1];
        yield ["Balade dans la forêt", 1, "Facile", self::AYMERIC_LIB_1];
        yield ["Balade en enfer", 10, "Difficile", self::MAEL_LIB_1];
        
    }
    
    private static function getLibrairiesData()
    {
        yield ["Librairie de Mael", self::MAEL_LIB_1, self::MAEL_MEM_1];
        yield ["Librairie d'Aymeric", self::AYMERIC_LIB_1, self::AYMERIC_MEM_1];
        
    }
    
    private static function getMembersData()
    {
        yield ["Mael", "DM depuis tout bébé", self::MAEL_MEM_1];
        yield ["Aymeric", "Nouveau fan !", self::AYMERIC_MEM_1];
        
    }
    
    public function load(ObjectManager $manager)
    {        
        foreach (self::getMembersData() as [$nom, $profil, $memberReference]) {
           $member = new Member();
           $member->setNom($nom);
           $member->setProfil($profil);
           $manager->persist($member);
           $manager->flush();
           $this->addReference($memberReference, $member);
        }
        foreach (self::getLibrairiesData() as [$title, $librairieReference, $memberReference]) {
            $member = $this->getReference($memberReference);
            $librairie = new Librairie();
            $librairie->setNom($title);
            $member->addLibrairie($librairie);
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
