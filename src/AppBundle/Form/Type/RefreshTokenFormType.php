<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Utils\TranslationNamespaceAwareTrait;

/**
 * RefreshTokenFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class RefreshTokenFormType extends DisabledCsrfProtectionFormType
{
    use TranslationNamespaceAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translationNamespace = $this->getTranslationNamespace();

        $builder
            ->add('refresh_token', null, [
                'label' => $translationNamespace . 'label.refresh_token',
            ])
        ;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'refresh_token';
    }
}