<?php

namespace Tests\AppBundle\Validator\Constraints;

use DateTime;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;

use AppBundle\Entity\Period;
use AppBundle\Validator\Constraints\PeriodFinishedAfterStart;
use AppBundle\Validator\Constraints\PeriodFinishedAfterStartValidator;

/**
 * PeriodFinishedAfterStartValidatorTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class PeriodFinishedAfterStartValidatorTest extends AbstractConstraintValidatorTest
{
    /**
     * @return array[]
     */
    public function validPeriodProvider(): array
    {
        $period1 = new Period();
        $period1->setStartedAt(new DateTime());
        $period1->setFinishedAt(null);

        $period2 = new Period();
        $period2->setStartedAt(new DateTime('@' . strtotime('-1 hour')));
        $period1->setFinishedAt(null);

        $period3 = new Period();
        $period3->setStartedAt(new DateTime('@' . strtotime('-1 hour')));
        $period3->setFinishedAt(new DateTime());

        $period4 = new Period();
        $hourAgoTimestamp = strtotime('-1 hour');
        $period4->setStartedAt(new DateTime('@' . $hourAgoTimestamp));
        $period4->setFinishedAt(new DateTime('@' . $hourAgoTimestamp));

        return [
            [$period1],
            [$period2],
            [$period3],
            [$period4],
        ];
    }

    /**
     * @return array[]
     */
    public function invalidPeriodProvider(): array
    {
        $period1 = new Period();
        $period1->setStartedAt(new DateTime());
        $period1->setFinishedAt(new DateTime('@' . strtotime('-1 hour')));

        $period2 = new Period();
        $period2->setStartedAt(null);
        $period2->setFinishedAt(new DateTime());

        return [
            [$period1],
            [$period2],
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
     * @param array $options
     *
     * @return PeriodFinishedAfterStart
     */
    protected function createConstraint(array $options): PeriodFinishedAfterStart
    {
        return new PeriodFinishedAfterStart($options);
    }

    /**
     * @return PeriodFinishedAfterStartValidator
     */
    protected function createValidator(): PeriodFinishedAfterStartValidator
    {
        return new PeriodFinishedAfterStartValidator();
    }
}