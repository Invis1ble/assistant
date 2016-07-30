<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * AuthenticationFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class AuthenticationFormType extends UserFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translationNamespace = $this->getTranslationNamespace();

        $builder
            ->add('username', null, [
                'label' => $translationNamespace . 'label.username',
            ])
            ->add('password', PasswordType::class, [
                'label' => $translationNamespace . 'label.password',
            ])
        ;
    }
}