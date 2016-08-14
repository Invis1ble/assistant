<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Task;
use AppBundle\Form\Type\TaskFormType;

/**
 * TaskController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskController extends FOSRestController
{
    /**
     * Get single task
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         401 = "Returned when unauthorized",
     *         403 = "Returned when not permitted",
     *         404 = "Returned when the task is not found"
     *     },
     *     requirements = {
     *         {
     *             "name" = "task",
     *             "dataType" = "UUID string",
     *             "description" = "Task ID"
     *         }
     *     }
     * )
     *
     * @Security("is_granted('show', task)")
     *
     * @Annotations\View()
     *
     * @param Task $task
     *
     * @return Task
     */
    public function getTaskAction(Task $task): Task
    {
        return $task;
    }

    /**
     * Patch existing task from the submitted data.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\TaskFormType",
     *         "name" = ""
     *     },
     *     requirements = {
     *         {
     *             "name" = "task",
     *             "dataType" = "UUID string",
     *             "description" = "Task ID"
     *         }
     *     },
     *     statusCodes = {
     *         204 = "Returned when successful",
     *         400 = "Returned when the form has errors",
     *         401 = "Returned when unauthorized",
     *         404 = "Returned when the task is not found"
     *     }
     * )
     *
     * @Security("is_granted('edit', task)")
     *
     * @Annotations\View()
     *
     * @param Request $request
     * @param Task    $task
     *
     * @return View|Form
     */
    public function patchTaskAction(Request $request, Task $task)
    {
        $form = $this->createForm(TaskFormType::class, $task);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $this->get('app.manager.task_manager')->save($task);

            return $this->routeRedirectView('api_get_task', [
                'task' => $task->getId(),
            ], Response::HTTP_NO_CONTENT);
        }

        return $form;
    }
}