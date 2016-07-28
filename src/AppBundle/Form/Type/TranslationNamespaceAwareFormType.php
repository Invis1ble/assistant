<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

/**
 * TranslationNamespaceAwareFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
abstract class TranslationNamespaceAwareFormType extends AbstractType
{
    /**
     * @var string
     */
    protected $translationNamespace;

    /**
     * @return string
     */
    public function getTranslationNamespace(): string
    {
        return $this->translationNamespace;
    }

    /**
     * @param string $translationNamespace
     *
     * @return TranslationNamespaceAwareFormType
     */
    public function setTranslationNamespace(string $translationNamespace): TranslationNamespaceAwareFormType
    {
        $this->translationNamespace = $translationNamespace;

        return $this;
    }
}