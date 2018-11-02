<?php

namespace App\Form;

use App\Entity\Wishes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('realizeEmail', TextType::class, [
                'attr' => ['placeholder' => 'E-mail adresa'],
                'label' => false,
            ])
            ->add('realizePhone', TextType::class, [
                'attr' => ['placeholder' => 'Broj mobitela'],
                'label' => false,
            ])
            ->add('realizeWish', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Opis'],
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Javi nam se'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wishes::class,
        ]);
    }
}
