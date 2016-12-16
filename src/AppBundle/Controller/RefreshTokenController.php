<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * RefreshTokenController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class RefreshTokenController extends FOSRestController
{
    /**
     * Refreshes corresponding JSON Web Token with the submitted Refresh Token and returns one.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\RefreshTokenFormType",
     *         "name" = ""
     *     },
     *     statusCodes = {
     *         200 = "Returned when the JWT is refreshed",
     *         401 = "Returned when the Refresh Token is invalid"
     *     }
     * )
     *
     * @Annotations\Route(path="refresh-tokens")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postRefreshTokenAction(Request $request): Response
    {
        return $this->get('gesdinet.jwtrefreshtoken')->refresh($request);
    }
}