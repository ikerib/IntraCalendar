<?php

namespace App\Controller\Admin;

use App\Entity\Schedule;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;

class ScheduleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Schedule::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title','Izenburua'),
//            TextareaField::new('body'),
            TextEditorField::new('body'),
            TextField::new('category', 'Mota'),
            DateTimeField::new('start', 'Hasi')->setFormat('Y-MM-dd HH:mm')->renderAsChoice(),
            DateTimeField::new('end', 'Amaitu')->setFormat('Y-MM-dd HH:mm')->renderAsChoice(),
            BooleanField::new('isAllDay'),
            AssociationField::new('calendar')
        ];
    }

}
