<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\Task;

/**
 * TaskFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskFormType extends AbstractType
{
    /**
     * @var string
     */
    protected $translationNamespace = 'form.task.';

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
                'empty_data' => Task::DEFAULT_RATE,
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
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Task',
            'intention' => $this->getName(),

            // Todo: Fix CSRF protection
            'csrf_protection' => false,
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'task';
    }

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
     * @return TaskFormType
     */
    public function setTranslationNamespace(string $translationNamespace)
    {
        $this->translationNamespace = $translationNamespace;

        return $this;
    }
}