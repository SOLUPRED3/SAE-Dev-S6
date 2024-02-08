<?php

namespace App\Controller\Admin;

use App\Entity\Emprunt;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EmpruntCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Emprunt::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('dateEmprunt')->setFormat('dd-MM-yyyy'),
            DateField::new('dateRetour')->setFormat('dd-MM-yyyy')->hideOnIndex(), // Hide on index page, as it is visible in the detail view
            AssociationField::new('emprunteur'),
            AssociationField::new('livre'),
        ];
    }

    //public function configureActions(Actions $actions): Actions
    //{
    //    // If the user is not a manager or an admin, disable the delete, new and edit actions
    //    if (!($this->isGranted('ROLE_MANAGER') or $this->isGranted('ROLE_ADMIN'))){
    //        $actions->disable(Action::DELETE, Action::NEW, Action::EDIT);
    //    }
    //    // If the user is a member, disable the edit action
    //    if ($this->isGranted('ROLE_LIBRARIAN')){
    //        $actions->disable(Action::EDIT);
    //    }
    //    return $actions;
    //}
}
