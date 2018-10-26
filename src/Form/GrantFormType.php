<?php

namespace App\Form;

use App\Entity\Wishes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GrantFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('realizeEmail', TextType::class, [
                'help' => 'Day of the week.',
                'attr' => ['placeholder' => 'Day'],
            ])
            ->add('realizePhone', TextType::class, [
                'help' => 'Time you stop working',
                'attr' => ['placeholder' => 'ime your company closes'],
            ])
            ->add('realizeWish', TextType::class, [
                'help' => 'Time you start working',
                'attr' => ['placeholder' => 'Time your company opens.'],
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
