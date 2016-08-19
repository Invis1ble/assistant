<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
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
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the user for which tasks are requested"
     *         }
     *     },
     *     headers = {
     *         {
     *             "name" = "Authorization",
     *             "default" = "Bearer ",
     *             "description" = "Authorization JSON Web Token",
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
     * @Annotations\Route(path="users/{id}/tasks")
     *
     * @Security("is_granted('task_list', fetchedUser)")
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
     * @param User                  $fetchedUser
     *
     * @return UserTaskCollection
     */
    public function getTasksAction(ParamFetcherInterface $paramFetcher, User $fetchedUser): UserTaskCollection
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        return new UserTaskCollection(
            $this->getDoctrine()->getRepository('AppBundle:Task')->findLatestCreatedBy($fetchedUser, $limit, $offset),
            $fetchedUser,
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
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the user for which tasks are requested"
     *         }
     *     },
     *     headers = {
     *         {
     *             "name" = "Authorization",
     *             "default" = "Bearer ",
     *             "description" = "Authorization JSON Web Token",
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
     * @Annotations\Route(path="users/{id}/tasks")
     *
     * @Security("is_granted('task_create', fetchedUser)")
     *
     * @Annotations\View()
     *
     * @param Request $request
     * @param User    $fetchedUser
     *
     * @return View|Form
     */
    public function postTaskAction(Request $request, User $fetchedUser)
    {
        $taskManager = $this->get('app.manager.task_manager');
        $task = $taskManager->createTask();
        $task->setUser($fetchedUser);

        $form = $this->createForm(TaskFormType::class, $task);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $taskManager->saveAndFlush($task);

            return $this->routeRedirectView('api_get_task', [
                'id' => $task->getId(),
            ]);
        }

        return $form;
    }
}