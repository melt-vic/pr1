<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserRegisteredType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nombre y apellidos'])
            ->add('email', EmailType::class, ['label' => 'Correo electrónico'])
            ->add('password', PasswordType::class, ['label' => 'Contraseña'])
            ->add('address', TextType::class, ['label' => 'Dirección'])
            ->add('save', SubmitType::class, ['label' => 'Checkout!'])
        ;
    }
}
