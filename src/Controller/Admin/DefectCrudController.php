<?php

namespace App\Controller\Admin;

use App\Entity\Defect;
use App\Entity\Reason;
use App\Controller\Admin\ReasonCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DefectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Defect::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id');
        yield TextField::new('item');
        yield TextField::new('bin_number');
        yield IntegerField::new('expected_quantity');
        yield IntegerField::new('actual_quantity');
        yield AssociationField::new('count'); 
        yield TextField::new('attachment_link');
        yield TextEditorField::new('comment');
        yield AssociationField::new('reason'); 
        yield BooleanField::new('isInvestigated'); 
        
    }
    
}

