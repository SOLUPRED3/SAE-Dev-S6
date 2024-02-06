<?php

namespace App\Controller\Admin;

use App\Entity\Livre;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class LivreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Livre::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            DateField::new('dateSortie')->setFormat('dd-MM-yyyy'),
            TextField::new('langue'),
            ImageField::new('photoCouverture')->setUploadDir('/public/uploads/photos'),
            AssociationField::new('auteurs'),
            AssociationField::new('categorie'),
            AssociationField::new('emprunts'),
            AssociationField::new('reservations'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        if (!($this->isGranted('ROLE_MANAGER') or $this->isGranted('ROLE_ADMIN'))){
            return $actions
                ->disable(Action::DELETE, Action::NEW, Action::EDIT);
        }else{
            return $actions;
        }
    }
}
