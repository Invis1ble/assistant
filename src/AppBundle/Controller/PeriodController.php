<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Period;
use AppBundle\Form\Type\TaskPeriodFormType;
use Symfony\Component\HttpFoundation\Response;

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

    /**
     * Patch existing period from the submitted data.
     *
     * @ApiDoc(
     *     statusCodes = {
     *         204 = "Returned when successful",
     *         400 = "Returned when the form has errors",
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
            $this->get('app.manager.period_manager')->save($period);

            return $this->routeRedirectView('api_get_period', [
                'period' => $period->getId(),
            ], Response::HTTP_NO_CONTENT);
        }

        return $form;
    }
}