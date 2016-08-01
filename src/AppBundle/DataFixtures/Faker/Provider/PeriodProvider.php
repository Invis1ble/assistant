<?php

namespace AppBundle\DataFixtures\Faker\Provider;

use AppBundle\Entity\Period;
use DateTime;
use LogicException;

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

        $filteredPeriods = array_filter($task->getPeriods()->toArray(), function (Period $taskPeriod) use ($period) {
            return $taskPeriod !== $period;
        });

        if (empty($filteredPeriods)) {
            $fromDate = $task->getCreatedAt();
        } else {
            /* @var $filteredPeriods Period[] */

            usort($filteredPeriods, function (Period $period1, Period $period2) {
                $period1FinishedAt = $period1->getFinishedAt();
                $period2FinishedAt = $period2->getFinishedAt();

                if ($period1FinishedAt === $period2FinishedAt) {
                    return 0;
                }

                return $period1FinishedAt < $period2FinishedAt ? 1 : -1;
            });

            $lastPeriodFinishedAt = $filteredPeriods[0]->getFinishedAt();

            if (null === $lastPeriodFinishedAt) {
                throw new LogicException('Last period should be finished');
            }

            $fromDate = $lastPeriodFinishedAt;
        }

        $fromTimestamp = $fromDate->getTimestamp();
        $toTimestamp = time();

        return new DateTime('@' . mt_rand($fromTimestamp, $toTimestamp));
    }

    /**
     * @param Period $period
     *
     * @return DateTime
     */
    public static function finishedAt(Period $period): DateTime
    {
        $fromTimestamp = $period->getStartedAt()
            ->getTimestamp();
        
        $toTimestamp = time();
        
        return new DateTime('@' . mt_rand($fromTimestamp, $toTimestamp));
    }
}