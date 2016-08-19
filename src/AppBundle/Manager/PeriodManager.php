<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Period;

/**
 * PeriodManager
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class PeriodManager extends AbstractManager
{
    /**
     * @return Period
     */
    public function createPeriod(): Period
    {
        return new Period();
    }

    /**
     * @param Period $period
     */
    public function save(Period $period)
    {
        $this->objectManager->persist($period);
    }

    /**
     * @param Period $period
     */
    public function saveAndFlush(Period $period)
    {
        $this->save($period);
        $this->objectManager->flush();
    }

    /**
     * @param Period $period
     * @param bool   $andFlush
     */
    public function remove(Period $period, bool $andFlush = true)
    {
        $this->objectManager->remove($period);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }
}