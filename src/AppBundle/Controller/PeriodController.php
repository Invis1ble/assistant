<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Period;

/**
 * PeriodController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class PeriodController extends FOSRestController
{
    /**
     * Get single period
     *
     * @ApiDoc(
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the period is not found"
     *     },
     *     requirements = {
     *         {
     *             "name" = "period",
     *             "dataType" = "UUID string",
     *             "description" = "Period ID"
     *         }
     *     }
     * )
     *
     * @Annotations\View()
     *
     * @param Period $period
     *
     * @return Period
     */
    public function getPeriodAction(Period $period): Period
    {
        return $period;
    }
}