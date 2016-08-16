<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use AppBundle\Entity\Period;

/**
 * PeriodFinishedAfterStartValidator
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @Annotation
 */
class PeriodFinishedAfterStartValidator extends ConstraintValidator
{
    /**
     * @param Period     $period
     * @param Constraint $constraint
     */
    public function validate($period, Constraint $constraint)
    {
        if (!$constraint instanceof PeriodFinishedAfterStart) {
            throw new UnexpectedTypeException($constraint, PeriodFinishedAfterStart::class);
        }

        if (!$period instanceof Period) {
            throw new UnexpectedTypeException($period, Period::class);
        }

        $startedAt = $period->getStartedAt();
        $finishedAt = $period->getFinishedAt();

        if (null === $startedAt || null !== $finishedAt && $finishedAt < $startedAt) {
            $this->context->addViolation($constraint->message);
        }
    }
}