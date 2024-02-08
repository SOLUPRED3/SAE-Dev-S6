<?php

namespace App\Controller\Admin;

use App\Entity\Auteur;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AuteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Auteur::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            Field::new('prenom'),
            Field::new('nom'),
            DateField::new('dateNaiss')->setFormat('dd-MM-yyyy'),
            DateField::new('dateDeces')->setFormat('dd-MM-yyyy'),
            Field::new('nationalite'),
            ImageField::new('photo')->setUploadDir('\public\uploads\photos'),
            TextField::new('description'),
            AssociationField::new('livres'), // Assuming a "livres" association exists in Auteur entity
            
        ];
    }
    
    //public function configureActions(Actions $actions): Actions
    //{
    //    if (!($this->isGranted('ROLE_MANAGER') or $this->isGranted('ROLE_ADMIN'))){
    //        return $actions
    //            ->disable(Action::DELETE, Action::NEW, Action::EDIT);
    //    }else{
    //        return $actions;
    //    }
    //}
}
