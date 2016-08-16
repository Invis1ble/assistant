<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * PeriodFinishedAfterStart
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @Annotation
 */
class PeriodFinishedAfterStart extends Constraint
{
    /**
     * @var string
     */
    public $message = 'The finish time of the period should be greater than start time.';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}