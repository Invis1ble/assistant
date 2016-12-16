<?php

namespace AppBundle\DataFixtures\Faker\Provider;

use DateTime;
use Faker\Provider\DateTime as DateTimeProvider;

use AppBundle\Entity\Period;

/**
 * PeriodProvider
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class PeriodProvider
{
    /**
     * @param Period $period
     *
     * @return DateTime
     */
    public static function startedAt(Period $period): DateTime
    {
        $task = $period->getTask();
        $periods = clone $task->getPeriods();
        $periods->removeElement($period);

        if ($periods->isEmpty()) {
            return DateTimeProvider::dateTimeBetween($task->getCreatedAt());
        }

        $periods = $periods->toArray();
        /* @var $periods Period[] */

        usort($periods, function (Period $period1, Period $period2) {
            return $period2->getFinishedAt() <=> $period1->getFinishedAt();
        });

        return DateTimeProvider::dateTimeBetween($periods[0]->getFinishedAt());
    }
}