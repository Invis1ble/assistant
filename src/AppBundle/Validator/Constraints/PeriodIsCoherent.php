<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * PeriodIsCoherent
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @Annotation
 */
class PeriodIsCoherent extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Period is incoherent.';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}