<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\{
    Period,
    Task
};
use AppBundle\EntityCollection\TaskPeriodCollection;

/**
 * TaskPeriodController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskPeriodController extends FOSRestController
{
    /**
     * List task's periods
     *
     * @ApiDoc(
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the task is not found"
     *     },
     *     requirements = {
     *         {
     *             "name" = "task",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the task for which periods are requested"
     *         }
     *     }
     * )
     *
     * @Annotations\View()
     *
     * @param Task $task
     *
     * @return TaskPeriodCollection
     */
    public function getPeriodsAction(Task $task): TaskPeriodCollection
    {
        return new TaskPeriodCollection(
            $task,
            $task->getPeriods()->toArray()
        );
    }
}