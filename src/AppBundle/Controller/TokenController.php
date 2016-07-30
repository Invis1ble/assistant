<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * TokenController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TokenController extends FOSRestController
{
    /**
     * Creates a new token from the submitted data and returns it.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\AuthenticationFormType",
     *         "name" = ""
     *     },
     *     statusCodes = {
     *         200 = "Returned when a new token is created",
     *         401 = "Returned when credentials are invalid"
     *     }
     * )
     *
     * @return Response
     */
    public function postTokenAction(): Response
    {
        return new Response('', Response::HTTP_UNAUTHORIZED);
    }
}