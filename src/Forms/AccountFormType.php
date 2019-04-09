<?php declare(strict_types=1);

namespace App\Forms;

use App\Entity\Account;
use FOS\UserBundle\Form\Type\RegistrationFormType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Representation of form for Account creation
 *
 * @package App\Form
 */
class AccountFormType extends AbstractType
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

    /** @inheritdoc */
    public function getParent(): string
    {
        return RegistrationFormType::class;
    }
}
