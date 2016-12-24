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

use AppBundle\Entity\Category;
use AppBundle\EntityCollection\CategoryTaskCollection;
use AppBundle\Form\Type\CategoryTaskFormType;

/**
 * CategoryTaskController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryTaskController extends FOSRestController
{
    /**
     * List category's tasks
     *
     * @ApiDoc(
     *     resource = true,
     *     requirements = {
     *         {
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the category for which tasks are requested"
     *         }
     *     },
     *     headers = {
     *         {
     *             "name" = "Authorization",
     *             "default" = "Bearer ",
     *             "description" = "api_doc.jwt",
     *         }
     *     },
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         401 = "Returned when unauthorized",
     *         403 = "Returned when not permitted",
     *         404 = "Returned when category is not found"
     *     }
     * )
     *
     * @Annotations\Route(path="categories/{id}/tasks")
     *
     * @Security("is_granted('task_list', category)")
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
     *     default=\AppBundle\Entity\Task::NUM_ITEMS,
     *     description="How many tasks to return."
     * )
     *
     * @Annotations\View()
     *
     * @param ParamFetcherInterface $paramFetcher
     * @param Category              $category
     *
     * @return CategoryTaskCollection
     */
    public function getTasksAction(ParamFetcherInterface $paramFetcher, Category $category): CategoryTaskCollection
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        return new CategoryTaskCollection(
            $this->getDoctrine()->getRepository('AppBundle:Task')->findLatestInCategory($category, $limit, $offset),
            $category,
            $offset,
            $limit
        );
    }

    /**
     * Creates a new task from the submitted data.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\CategoryTaskFormType",
     *         "name" = ""
     *     },
     *     requirements = {
     *         {
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the category for which task is created"
     *         }
     *     },
     *     headers = {
     *         {
     *             "name" = "Authorization",
     *             "default" = "Bearer ",
     *             "description" = "api_doc.jwt",
     *         }
     *     },
     *     statusCodes = {
     *         201 = "Returned when a new task is created",
     *         400 = "Returned when the form has errors",
     *         401 = "Returned when unauthorized",
     *         403 = "Returned when not permitted",
     *         404 = "Returned when category is not found"
     *     }
     * )
     *
     * @Annotations\Route(path="categories/{id}/tasks")
     *
     * @Security("is_granted('task_create', category)")
     *
     * @Annotations\View()
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return View|Form
     */
    public function postTaskAction(Request $request, Category $category)
    {
        $taskManager = $this->get('app.manager.task_manager');
        $task = $taskManager->createTask();
        $task->setCategory($category);

        $form = $this->createForm(CategoryTaskFormType::class, $task);
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