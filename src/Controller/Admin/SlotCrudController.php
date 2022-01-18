<?php

namespace App\Controller\Admin;

use App\Entity\Slot;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class SlotCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Slot::class;
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
