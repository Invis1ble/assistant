<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

use AppBundle\Entity\Period;

/**
 * PeriodIsCoherentValidator
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @Annotation
 */
class PeriodIsCoherentValidator extends ConstraintValidator
{
    /**
     * @param Period     $period
     * @param Constraint $constraint
     */
    public function validate($period, Constraint $constraint)
    {
        if (!$constraint instanceof PeriodIsCoherent) {
            throw new UnexpectedTypeException($constraint, PeriodIsCoherent::class);
        }

        if (!$period instanceof Period) {
            throw new UnexpectedTypeException($period, Period::class);
        }

        $startedAt = $period->getStartedAt();
        $finishedAt = $period->getFinishedAt();

        if (null !== $finishedAt && $finishedAt < $startedAt) {
            $this->context->addViolation($constraint->message);
            return;
        }

        $task = $period->getTask();
        $periods = clone $task->getPeriods();
        $periods->removeElement($period);

        if ($periods->isEmpty()) {
            if ($startedAt < $task->getCreatedAt()) {
                $this->context->addViolation($constraint->message);
            }

            return;
        }

        $periods = $periods->toArray();
        /* @var $periods Period[] */

        usort($periods, function (Period $period1, Period $period2) {
            return $period2->getFinishedAt() <=> $period1->getFinishedAt();
        });

        $lastPeriodFinishedAt = $periods[0]->getFinishedAt();

        if (null === $lastPeriodFinishedAt || $startedAt < $lastPeriodFinishedAt) {
            $this->context->addViolation($constraint->message);
        }
    }
}