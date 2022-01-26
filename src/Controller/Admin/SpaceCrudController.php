<?php

namespace App\Controller\Admin;

use App\Entity\Space;
use App\Service\AdminActions;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use Symfony\Component\DomCrawler\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SpaceCrudController extends AbstractCrudController
{
    private AdminActions $adminAction;

    public function __construct(AdminActions $adminAction)
    {
        $this->adminAction = $adminAction;
    }
    
    public static function getEntityFqcn(): string
    {
        return Space::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $this->adminAction->configureActions($actions);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
        ->setPageTitle('index', 'Liste des %entity_label_plural%')
        ->setEntityLabelInSingular('espace')
        ->setEntityLabelInPlural('espaces');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // FormField::addPanel('Utilisateur')->setIcon('fa fa-home'),
            IdField::new('id')
                ->hideOnIndex()
                ->hideOnForm(),
            TextField::new('name', 'Titre de l\'espace'),
            TextField::new('category', 'Type d\'espace'),
            TextEditorField::new('description', 'Détails de l\'offre')
                ->hideOnIndex(),
            TextField::new('address', 'Adresse'),
            TextField::new('location', 'Ville'),
            IntegerField::new('price', 'Prix'),
            IntegerField::new('capacity', 'Capacité'),
            ImageField::new('photoFile', 'Photo')
                ->hideOnIndex()
                ->setUploadDir('assets/images'),
        ];
    }
}
