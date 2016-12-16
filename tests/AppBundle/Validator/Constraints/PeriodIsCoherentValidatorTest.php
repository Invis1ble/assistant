<?php

namespace Tests\AppBundle\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

use AppBundle\Entity\{
    Period,
    Task
};
use AppBundle\Validator\Constraints\{
    PeriodIsCoherent,
    PeriodIsCoherentValidator
};

/**
 * PeriodIsCoherentValidatorTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group unit
 */
class PeriodIsCoherentValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @return array[]
     */
    public function validPeriodProvider(): array
    {
        $monthAgo = new DateTime('-1 month');
        $hourAgo = new DateTime('-1 hour');
        $now = new DateTime();
        
        $task1 = $this->createTask($monthAgo);
        $period1 = $this->createPeriod($task1, $now);

        $task2 = $this->createTask($monthAgo);
        $period2 = $this->createPeriod($task2, $hourAgo);

        $task3 = $this->createTask($monthAgo);
        $period3 = $this->createPeriod($task3, $hourAgo, $now);

        $task4 = $this->createTask($monthAgo);
        $period4 = $this->createPeriod($task4, $hourAgo, $hourAgo);

        $task5 = $this->createTask($monthAgo);
        $period5 = $this->createPeriod($task5, $task5->getCreatedAt());

        $task6 = $this->createTask($monthAgo);
        $period6 = $this->createPeriod($task6, $task6->getCreatedAt(), $hourAgo);
        $period7 = $this->createPeriod($task6, $period6->getFinishedAt());

        return [
            [$period1],
            [$period2],
            [$period3],
            [$period4],
            [$period5],
            [$period7],
        ];
    }

    /**
     * @return array[]
     */
    public function invalidPeriodProvider(): array
    {
        $monthAgo = new DateTime('-1 month');
        $hourAgo = new DateTime('-1 hour');
        $now = new DateTime();
        
        $task1 = $this->createTask($monthAgo);
        $period1 = $this->createPeriod($task1, $now, $hourAgo);
        
        $task2 = $this->createTask($hourAgo);
        $period2 = $this->createPeriod($task2, $monthAgo);
        
        $task3 = $this->createTask($monthAgo);
        $this->createPeriod($task3, $hourAgo, $now);
        $period3 = $this->createPeriod($task3, $hourAgo);
        
        $task4 = $this->createTask($monthAgo);
        $this->createPeriod($task4, $hourAgo);
        $period4 = $this->createPeriod($task4, $now);
        
        return [
            [$period1],
            [$period2],
            [$period3],
            [$period4],
        ];
    }

    /**
     * @param Period $period
     *
     * @dataProvider validPeriodProvider
     */
    public function testValidPeriod(Period $period)
    {
        $this->validator->validate($period, $this->createConstraint([]));
        $this->assertNoViolation();
    }

    /**
     * @param Period $period
     *
     * @dataProvider invalidPeriodProvider
     */
    public function testInvalidPeriod(Period $period)
    {
        $constraint = $this->createConstraint([]);
        $constraint->message = 'Constraint Message';

        $this->validator->validate($period, $constraint);

        $this->buildViolation('Constraint Message')
            ->assertRaised();
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testThrowsUnexpectedTypeExceptionIfValueIsNotAPeriod()
    {
        $this->validator->validate('some value', $this->createConstraint([]));
    }

    /**
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     */
    public function testThrowsUnexpectedTypeExceptionIfUnacceptableConstraintPassed()
    {
        $this->validator->validate(new Period(), new class extends Constraint {});
    }

    /**
     * @param array $options
     *
     * @return PeriodIsCoherent
     */
    protected function createConstraint(array $options): PeriodIsCoherent
    {
        return new PeriodIsCoherent($options);
    }

    /**
     * @return PeriodIsCoherentValidator
     */
    protected function createValidator(): PeriodIsCoherentValidator
    {
        return new PeriodIsCoherentValidator();
    }

    /**
     * @param DateTime $createdAt
     *
     * @return Task
     */
    protected function createTask(DateTime $createdAt): Task
    {
        $task = new Task();
        $task->setCreatedAt($createdAt);
        
        return $task;
    }

    /**
     * @param Task          $task
     * @param DateTime      $startedAt
     * @param DateTime|null $finishedAt
     *
     * @return Period
     */
    protected function createPeriod(Task $task, DateTime $startedAt, DateTime $finishedAt = null): Period
    {
        $period = new Period();
        $period->setStartedAt($startedAt);
        $period->setFinishedAt($finishedAt);
        $period->setTaskAndAddSelfToPeriodsCollection($task);
        
        return $period;
    }
}