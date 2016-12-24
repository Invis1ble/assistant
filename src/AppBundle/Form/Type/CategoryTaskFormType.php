<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Utils\TranslationNamespaceAwareTrait;

/**
 * CategoryTaskFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryTaskFormType extends DisabledCsrfProtectionFormType
{
    use TranslationNamespaceAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translationNamespace = $this->getTranslationNamespace();

        $builder
            ->add('title', null, [
                'label' => $translationNamespace . 'label.title',
            ])
            ->add('description', null, [
                'label' => $translationNamespace . 'label.description',
            ])
            ->add('rate', MoneyType::class, [
                'label' => $translationNamespace . 'label.rate',
                'currency' => 'USD',
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
            'data_class' => 'AppBundle\Entity\Task',
            'intention' => $this->getName(),
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'task';
    }
}