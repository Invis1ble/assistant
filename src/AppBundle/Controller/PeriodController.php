<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Period;
use AppBundle\Form\Type\TaskPeriodFormType;

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
     *     resource = true,
     *     requirements = {
     *         {
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "Period ID"
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
     *         404 = "Returned when the period is not found"
     *     }
     * )
     *
     * @Annotations\Route(path="periods/{id}")
     *
     * @Security("is_granted('show', period)")
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

    /**
     * Patch existing period from the submitted data.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\TaskPeriodFormType",
     *         "name" = ""
     *     },
     *     requirements = {
     *         {
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "Period ID"
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
     *         204 = "Returned when successful",
     *         400 = "Returned when the form has errors",
     *         401 = "Returned when unauthorized",
     *         404 = "Returned when the period is not found"
     *     }
     * )
     *
     * @Annotations\Route(path="periods/{id}")
     *
     * @Security("is_granted('edit', period)")
     *
     * @Annotations\View()
     *
     * @param Request $request
     * @param Period  $period
     *
     * @return View|Form
     */
    public function patchPeriodAction(Request $request, Period $period)
    {
        $form = $this->createForm(TaskPeriodFormType::class, $period);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $this->get('app.manager.period_manager')->saveAndFlush($period);

            return $this->routeRedirectView('api_get_period', [
                'id' => $period->getId(),
            ], Response::HTTP_NO_CONTENT);
        }

        return $form;
    }
}