<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\DataTransformer\DateTimeToTimestampTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startedAt', TextType::class)
            ->add('finishedAt', TextType::class)
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
}