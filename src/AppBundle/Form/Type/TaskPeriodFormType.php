<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * TaskPeriodFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskPeriodFormType extends AbstractType
{
    /**
     * @var string
     */
    protected $translationNamespace = 'form.task_period.';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translationNamespace = $this->getTranslationNamespace();

        $builder
            ->add('startedAt', null, [
                'html5' => true,
                'widget' => 'single_text',
                'invalid_message' => 'period.started_at.invalid',
                'label' => $translationNamespace . 'label.started_at',
            ])
            ->add('finishedAt', null, [
                'required' => false,
                'html5' => true,
                'widget' => 'single_text',
                'invalid_message' => 'period.finished_at.invalid',
                'label' => $translationNamespace . 'label.finished_at',
            ])
        ;

        $dateTimeToTimestampTransformer = new DateTimeToTimestampTransformer();

        $builder
            ->get('startedAt')
            ->addModelTransformer($dateTimeToTimestampTransformer)
        ;

        $builder
            ->get('finishedAt')
            ->addModelTransformer($dateTimeToTimestampTransformer)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Period',
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
        return 'task_period';
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
     * @return TaskPeriodFormType
     */
    public function setTranslationNamespace(string $translationNamespace)
    {
        $this->translationNamespace = $translationNamespace;

        return $this;
    }
}