<?php

namespace App\Controller\Admin;

use App\Entity\Slot;
use App\Service\AdminActions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SlotCrudController extends AbstractCrudController
{
    private AdminActions $adminAction;

    public function __construct(AdminActions $adminAction)
    {
        $this->adminAction = $adminAction;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->adminAction->configureActions($actions);
    }
    
    public static function getEntityFqcn(): string
    {
        return Slot::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle('index', 'Liste des %entity_label_plural%')
        ->setEntityLabelInSingular('créneau')
        ->setEntityLabelInPlural('créneaux');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addPanel('Créneaux')->setIcon('fa fa-calendar-o'),
            IdField::new('id')
                ->hideOnIndex()
                ->hideOnForm(),
            IntegerField::new('price', 'Prix'),
            TextField::new('slotTime', 'Créneaux'),
            TextField::new('space', 'Annonce'),
            TextField::new('owner', 'Bailleur'),
        ];
    }
}
