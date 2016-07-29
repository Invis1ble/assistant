<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Task;
use AppBundle\EntityCollection\TaskCollection;
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
     * List all tasks
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful"
     *     }
     * )
     *
     * @Annotations\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     nullable=true,
     *     description="Offset from which to start listing tasks."
     * )
     * @Annotations\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default=Task::NUM_ITEMS,
     *     description="How many tasks to return."
     * )
     *
     * @Annotations\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     *
     * @return TaskCollection
     */
    public function getTasksAction(ParamFetcherInterface $paramFetcher): TaskCollection
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        return new TaskCollection(
            $this->getDoctrine()->getRepository('AppBundle:Task')->findLatest($limit, $offset),
            $offset,
            $limit
        );
    }

    /**
     * Get single task
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
     *             "description" = "Task ID"
     *         }
     *     }
     * )
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
     * Creates a new task from the submitted data.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\TaskFormType",
     *         "name" = ""
     *     },
     *     statusCodes = {
     *         201 = "Returned when a new task is created",
     *         400 = "Returned when the form has errors"
     *     }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request
     *
     * @return View|Form
     */
    public function postTaskAction(Request $request)
    {
        $taskManager = $this->get('app.manager.task_manager');
        $task = $taskManager->createTask();
        $task->setUser($this->getUser());

        $form = $this->createForm(TaskFormType::class, $task);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $taskManager->save($task);

            return $this->routeRedirectView('api_get_task', [
                'task' => $task->getId(),
            ]);
        }

        return $form;
    }
}