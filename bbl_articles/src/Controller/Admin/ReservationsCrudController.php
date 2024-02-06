<?php

namespace App\Controller\Admin;

use App\Entity\Reservations;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReservationsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservations::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            DateField::new('dateResa')->setFormat('dd-MM-yyyy'),
            AssociationField::new('livre'),
            AssociationField::new('reservateur'),
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
