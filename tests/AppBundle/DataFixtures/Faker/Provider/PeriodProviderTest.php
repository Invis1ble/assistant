<?php

namespace Tests\AppBundle\DataFixtures\Faker\Provider;

use DateTime;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

use AppBundle\DataFixtures\Faker\Provider\PeriodProvider;
use AppBundle\Entity\Task;
use AppBundle\Entity\Period;

/**
 * PeriodProviderTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class PeriodProviderTest extends KernelTestCase
{
    /**
     * @return array[]
     */
    public function startedAtProvider(): array
    {
        $nowTimestamp = time();
        $nowDateTime = new DateTime('@' . $nowTimestamp);

        $task1 = new Task();
        $task1CreatedAt = new DateTime('@' . ($nowTimestamp - 10));
        $task1->setCreatedAt($task1CreatedAt);

        $task1Period = new Period();
        $task1Period->setTaskAndAddSelfToPeriodsCollection($task1);

        $task2 = new Task();
        $task2CreatedAt = new DateTime('@' . ($nowTimestamp - 10));
        $task2->setCreatedAt($task2CreatedAt);

        $task2Period1FinishedAt = new DateTime('@' . ($nowTimestamp - 1));

        (new Period())
            ->setStartedAt(new DateTime('@' . ($nowTimestamp - 5)))
            ->setFinishedAt($task2Period1FinishedAt)
            ->setTaskAndAddSelfToPeriodsCollection($task2);

        $task2Period = new Period();
        $task2Period->setTaskAndAddSelfToPeriodsCollection($task2);

        return [
            [$task1Period, $task1CreatedAt, $nowDateTime],
            [$task2Period, $task2Period1FinishedAt, $nowDateTime],
        ];
    }

    /**
     * @param Period   $period
     * @param DateTime $startDateTime
     * @param DateTime $endDateTime
     *
     * @dataProvider startedAtProvider
     */
    public function testStartedAt(Period $period, DateTime $startDateTime, DateTime $endDateTime)
    {
        $startedAt = PeriodProvider::startedAt($period);

        $this->assertGreaterThanOrEqual($startDateTime, $startedAt);
        $this->assertLessThanOrEqual($endDateTime, $startedAt);
    }
}