<?php

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditUserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('email')
                ->add('name')
                ->add('surname')
                ->add('roles', ChoiceType::class, [
                    'multiple' => true,
                    'choices'  => [
                        'Active (can access control panel'    => [
                            'Moderator'     => 'ROLE_MOD',
                            'Administrator' => 'ROLE_ADMIN',
                        ],
                        'Passive (cannot access admin panel)' => [
                            'Casual user' => 'ROLE_USER',
                        ],
                    ],
                ])
                ->add('profileImage', FileType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
