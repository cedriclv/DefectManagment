<?php

namespace App\Controller\Admin;

use App\Entity\Count;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CountCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Count::class;
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
