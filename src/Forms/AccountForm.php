<?php declare(strict_types=1);

namespace App\Forms;

use App\Entity\Account;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Representation of form for Account creation
 *
 * @package App\Form
 */
class AccountForm extends AbstractType
{
    /**
     * Valid translation domain
     */
    private const TRANSLATION_DOMAIN = 'new-user-form';

    /** @inheritdoc */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name')
                ->add('surname')
                ->add('email')
                ->add('plainPassword', PasswordType::class, [
                    'mapped'      => false,
                    'constraints' => [
                        new NotBlank(['message' => 'form.errors.password.blank',]),
                        new Length([
                            'min'        => 8,
                            'minMessage' => 'form.errors.password.length',
                        ]),
                    ],
                ])
                ->add('profileImage', FileType::class, [
                    'required' => false,
                ]);
    }

    /** @inheritdoc */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class'         => Account::class,
            'translation_domain' => self::TRANSLATION_DOMAIN,
        ]);
    }
}
