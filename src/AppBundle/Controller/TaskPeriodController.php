<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Task;
use AppBundle\EntityCollection\TaskPeriodCollection;
use AppBundle\Form\Type\TaskPeriodFormType;

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
     *         404 = "Returned when the task is not found",
     *         401 = "Returned when unauthorized"
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

    /**
     * Creates a new period from the submitted data.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\TaskPeriodFormType",
     *         "name" = ""
     *     },
     *     statusCodes = {
     *         201 = "Returned when a new period is created",
     *         400 = "Returned when the form has errors",
     *         404 = "Returned when the task is not found",
     *         401 = "Returned when unauthorized"
     *     },
     *     requirements = {
     *         {
     *             "name" = "task",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the task for which period is created"
     *         }
     *     }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request
     * @param Task    $task
     *
     * @return View|Form
     */
    public function postPeriodAction(Request $request, Task $task)
    {
        $periodManager = $this->get('app.manager.period_manager');
        $period = $periodManager->createPeriod();
        $period->setTask($task);

        $form = $this->createForm(TaskPeriodFormType::class, $period);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $periodManager->save($period);

            return $this->routeRedirectView('api_get_period', [
                'period' => $period->getId(),
            ]);
        }

        return $form;
    }
}