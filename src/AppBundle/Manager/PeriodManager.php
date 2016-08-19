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
     */
    public function remove(Period $period)
    {
        $this->objectManager->remove($period);
    }

    /**
     * @param Period $period
     */
    public function removeAndFlush(Period $period)
    {
        $this->remove($period);
        $this->objectManager->flush();
    }
}