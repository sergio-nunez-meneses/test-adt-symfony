<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('roles', TextType::class);

        $builder
            ->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function($roles_to_string)
                {
                    // array to a string conversion
                    return implode(', ', $roles_to_string);
                },
                function($roles_to_array)
                {
                    // string to array conversion
                    return explode(', ', $roles_to_array);
                }
            ));

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event)
                {
                    $user = $event->getData();
                    $form = $event->getForm();

                    // if no user object has been passed to the form
                    if (!$user || $user->getId() === null)
                    {
                        $password = $this->passwordGenerator(16);

                        $form->add('password', HiddenType::class, [
                            'data' => $password
                        ]);
                    }
                });
        }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    private function passwordGenerator(int $length)
    {
        return bin2hex(random_bytes($length));
    }
}