<?php

namespace App\Form;

use App\Entity\Wishes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WishFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'help' => 'Day of the week.',
                'attr' => ['placeholder' => 'Day'],
            ])
            ->add('email', TextType::class, [
                'help' => 'Time you start working',
                'attr' => ['placeholder' => 'Time your company opens.'],
            ])
            ->add('address', TextType::class, [
                'help' => 'Time you stop working',
                'attr' => ['placeholder' => 'ime your company closes'],
            ])
            ->add('location', TextType::class, [
                'help' => 'Is company working on this day?',
                'required' => false,
            ])
            ->add('wish', TextType::class, [
                'help' => 'Is company working on this day?',
                'required' => false,
            ])
            ->add('wishImage', FileType::class, [
                'help' => 'Is company working on this day?',
                'required' => false,
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Wishes::class,
        ]);
    }
}
