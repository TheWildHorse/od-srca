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

class WishFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nickname', TextType::class, [
                'attr' => ['placeholder' => 'Ime'],
                'label' => false,
            ])
            ->add('location', TextType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Koji je tvoj grad?'],
            ])
            ->add('wish', TextareaType::class, [
                'attr' => ['placeholder' => 'Napiši nešto o sebi'],
                'label' => false,
                'required' => false,
            ])
            ->add('wishImage', FileType::class, [
                'label' => "Dodaj sliku",
                'required' => false,
            ])
            ->add('name', TextType::class, [
                'attr' => ['placeholder' => 'Ime'],
                'label' => false,
                'required' => false,
            ])
            ->add('lastname', TextType::class, [
                'attr' => ['placeholder' => 'Prezime'],
                'required' => false,
                'label' => false,
            ])
            ->add('email', TextType::class, [
                'attr' => ['placeholder' => 'E-mail'],
                'required' => false,
                'label' => false,
            ])
            ->add('address', TextType::class, [
                'attr' => ['placeholder' => 'Adresa'],
                'required' => false,
                'label' => false,
            ])
            ->add('wishPhone', TextType::class, [
                'attr' => ['placeholder' => 'Broj mobitela'],
                'required' => false,
                'label' => false,
            ])
            ->add('submit', SubmitType::class,[
                'label' => 'Zaželi'
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
