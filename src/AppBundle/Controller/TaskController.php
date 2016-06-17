<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\Controller\Annotations;

use AppBundle\Entity\Task;
use AppBundle\EntityCollection\TaskCollection;

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
    public function getTasksAction(ParamFetcherInterface $paramFetcher)
    {
        $offset = $paramFetcher->get('offset');
        $limit = $paramFetcher->get('limit');

        return new TaskCollection(
            $this->getDoctrine()->getRepository('AppBundle:Task')->findLatest($limit, $offset),
            $offset,
            $limit
        );
    }
}