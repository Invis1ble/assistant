<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Utils\TranslationNamespaceAwareTrait;

/**
 * UserFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserFormType extends DisabledCsrfProtectionFormType
{
    use TranslationNamespaceAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translationNamespace = $this->getTranslationNamespace();

        $builder
            ->add('username', null, [
                'label' => $translationNamespace . 'label.username',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => $translationNamespace . 'label.password',
                ],
                'second_options' => [
                    'label' => $translationNamespace . 'label.password_repeated',
                ],
                'invalid_message' => 'user.password.mismatch',
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\User',
            'intention' => $this->getName(),
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'user';
    }
}