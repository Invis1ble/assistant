<?php

namespace Utils;

/**
 * TranslationNamespaceAwareTrait
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
trait TranslationNamespaceAwareTrait
{
    /**
     * @var string
     */
    protected $translationNamespace = '';

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
     * @return $this
     */
    public function setTranslationNamespace(string $translationNamespace)
    {
        $this->translationNamespace = $translationNamespace;

        return $this;
    }
}