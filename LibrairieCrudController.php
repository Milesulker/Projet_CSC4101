<?php

namespace App\Controller\Admin;

use App\Entity\Librairie;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class LibrairieCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Librairie::class;
    }
   
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('Nom'),
            AssociationField::new('member'),
            AssociationField::new('livre')
        ];
    }
}
