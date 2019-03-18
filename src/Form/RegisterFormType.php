<?php declare(strict_types=1);

namespace App\Form;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterFormType extends AbstractType
{
    /** @inheritdoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name')
                ->add('surname')
                ->add('email')
                ->add('plainPassword', PasswordType::class, [
                    'mapped'      => false,
                    'constraints' => [
                        new NotBlank(['message' => 'Choose a password!',]),
                        new Length([
                            'min'        => 8,
                            'minMessage' => 'Come on, you can think of a password longer than that!',
                        ]),
                    ],
                ])
                ->add('profileImage', FileType::class);
    }

    /** @inheritdoc */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Account::class,
        ]);
    }
}
