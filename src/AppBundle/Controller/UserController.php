<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\User;
use AppBundle\Form\Type\UserFormType;

/**
 * UserController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserController extends FOSRestController
{
    /**
     * Creates a new user from the submitted data.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\UserFormType",
     *         "name" = ""
     *     },
     *     statusCodes = {
     *         201 = "Returned when a new user is created",
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
    public function postUserAction(Request $request)
    {
        $userManager = $this->get('app.manager.user_manager');
        $user = $userManager->createUser();

        $form = $this->createForm(UserFormType::class, $user);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $userManager->save($user);

            return $this->routeRedirectView('api_get_user', [
                'user' => $user->getId(),
            ]);
        }

        return $form;
    }

    /**
     * Get single user
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         404 = "Returned when the user is not found",
     *         401 = "Returned when unauthorized"
     *     },
     *     requirements = {
     *         {
     *             "name" = "user",
     *             "dataType" = "UUID string",
     *             "description" = "User ID"
     *         }
     *     }
     * )
     *
     * @Annotations\View()
     *
     * @param User $user
     *
     * @return User
     */
    public function getUserAction(User $user): User
    {
        return $user;
    }
}