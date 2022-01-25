<?php

namespace App\Controller\Admin;

use App\Entity\Space;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class SpaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Space::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->hideOnIndex(),
            TextField::new('name', "Titre de l'espace"),
            TextEditorField::new('description', "Description de l'espace"),
        ];
    }
}
