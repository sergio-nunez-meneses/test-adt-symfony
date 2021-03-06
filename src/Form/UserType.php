<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\CallbackTransformer;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('roles', TextType::class);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function($roles_to_array)
                {
                    // array to a string conversion
                    return implode(', ', $roles_to_array);
                },
                function($roles_to_string)
                {
                    // string to array conversion
                    return explode(', ', $roles_to_string);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}