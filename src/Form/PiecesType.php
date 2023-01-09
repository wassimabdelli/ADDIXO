<?php

namespace App\Form;

use App\Entity\Pieces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PiecesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            //->add('prductnumber')
            ->add('societe')
            ->add('date',DateType::class,array(
                'disabled'=>true
            ))
            //->add('utilisateur')
            //->add('drawingnumber')
            ->add('productionnum')
           // ->add('typekeylist')
            ->add('description')
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pieces::class,
        ]);
    }
}
