<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\User;
use AppBundle\Entity\Task;
use AppBundle\EntityCollection\UserTaskCollection;
use AppBundle\Form\Type\TaskFormType;

/**
 * UserTaskController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserTaskController extends FOSRestController
{
    /**
     * List user's tasks
     *
     * @ApiDoc(
     *     resource = true,
     *     requirements = {
     *         {
     *             "name" = "user",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the user for which tasks are requested"
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         401 = "Returned when unauthorized",
     *         403 = "Returned when not permitted",
     *         404 = "Returned when user is not found"
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
     * @param User                  $user
     *
     * @return UserTaskCollection
     */
    public function getTasksAction(ParamFetcherInterface $paramFetcher, User $user): UserTaskCollection
    {
        $this->denyAccessUnlessGranted('task_list', $user);

        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        return new UserTaskCollection(
            $this->getDoctrine()->getRepository('AppBundle:Task')->findLatest($user, $limit, $offset),
            $user,
            $offset,
            $limit
        );
    }

    /**
     * Creates a new task from the submitted data.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\TaskFormType",
     *         "name" = ""
     *     },
     *     requirements = {
     *         {
     *             "name" = "user",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the user for which tasks are requested"
     *         }
     *     },
     *     statusCodes = {
     *         201 = "Returned when a new task is created",
     *         400 = "Returned when the form has errors",
     *         401 = "Returned when unauthorized",
     *         403 = "Returned when not permitted",
     *         404 = "Returned when user is not found"
     *     }
     * )
     *
     * @Annotations\View()
     *
     * @param Request $request
     * @param User    $user
     *
     * @return View|Form
     */
    public function postTaskAction(Request $request, User $user)
    {
        $this->denyAccessUnlessGranted('task_create', $user);

        $taskManager = $this->get('app.manager.task_manager');
        $task = $taskManager->createTask();
        $task->setUser($user);

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