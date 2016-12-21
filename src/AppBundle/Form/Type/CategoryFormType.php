<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Category;
use Utils\TranslationNamespaceAwareTrait;

/**
 * CategoryFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryFormType extends DisabledCsrfProtectionFormType
{
    use TranslationNamespaceAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translationNamespace = $this->getTranslationNamespace();

        $builder
            ->add('name', null, [
                'label' => $translationNamespace . 'label.name',
            ])
            ->add('description', null, [
                'label' => $translationNamespace . 'label.description',
            ])
            ->add('rate', MoneyType::class, [
                'empty_data' => Category::DEFAULT_RATE,
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
            'data_class' => 'AppBundle\Entity\Category',
            'intention' => $this->getName(),
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'category';
    }
}