<?php

namespace App\Form;

use App\Entity\Count;
use App\Entity\Defect;
use App\Entity\Reason;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DefectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('bin_number')
            ->add('item')
            ->add('expected_quantity')
            ->add('actual_quantity')
            ->add('comment')
            ->add('attachment_link')
            ->add('isInvestigated')
            //->add('count')
            ->add('REASON', EntityType::class, [
                // looks for choices from this entity
                'class' => Reason::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('count', EntityType::class, [
                // looks for choices from this entity
                'class' => Count::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'id',
            
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Defect::class,
        ]);
    }
}
