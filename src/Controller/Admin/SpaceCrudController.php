<?php

namespace App\Controller\Admin;

use App\Entity\Space;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SpaceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Space::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
