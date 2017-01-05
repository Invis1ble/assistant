<?php

namespace AppBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Exception\RuntimeException;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\User;
use AppBundle\Repository\CategoryRepository;

use Utils\TranslationNamespaceAwareTrait;

/**
 * TaskFormType
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016-2017, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskFormType extends DisabledCsrfProtectionFormType
{
    use TranslationNamespaceAwareTrait;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!$options['user'] instanceof User) {
            throw new RuntimeException('User is not defined');
        }

        $user = $options['user'];

        $translationNamespace = $this->getTranslationNamespace();

        $builder
            ->add('category', EntityType::class, [
                'class' => 'AppBundle:Category',
                'query_builder' => function (CategoryRepository $categoryRepository) use ($user) {
                    $alias = $categoryRepository->getRootAlias();

                    return $categoryRepository->createQueryBuilder($alias)
                        ->andWhere($alias . '.user = :' . $alias . '__user')
                        ->setParameter($alias . '__user', $user)
                        ->addOrderBy($alias . '.name', 'ASC')
                    ;
                },
                'label' => $translationNamespace . 'label.category',
                'invalid_message' => 'task.category.invalid',
            ])
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
            'user' => null,
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