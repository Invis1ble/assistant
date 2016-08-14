<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Utils\TranslationNamespaceAwareTrait;

/**
 * TaskPeriodFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskPeriodFormType extends DisabledCsrfProtectionFormType
{
    use TranslationNamespaceAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $translationNamespace = $this->getTranslationNamespace();
        $dateTimeToTimestampTransformer = new DateTimeToTimestampTransformer();

        $builder
            ->add(
                $builder
                    ->create('startedAt', TextType::class, [
                        'invalid_message' => 'period.started_at.invalid',
                        'label' => $translationNamespace . 'label.started_at',
                    ])
                    ->addModelTransformer($dateTimeToTimestampTransformer)
            )
            ->add(
                $builder
                    ->create('finishedAt', TextType::class, [
                        'required' => false,
                        'invalid_message' => 'period.finished_at.invalid',
                        'label' => $translationNamespace . 'label.finished_at',
                    ])
                    ->addModelTransformer($dateTimeToTimestampTransformer)
            )
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'data_class' => 'AppBundle\Entity\Period',
            'intention' => $this->getName(),
        ]);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'task_period';
    }
}