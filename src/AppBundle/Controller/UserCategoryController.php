<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\User;
use AppBundle\EntityCollection\UserCategoryCollection;
use AppBundle\Form\Type\CategoryFormType;

/**
 * UserCategoryController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserCategoryController extends FOSRestController
{
    /**
     * List user's categories
     *
     * @ApiDoc(
     *     resource = true,
     *     requirements = {
     *         {
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the user for which categories are requested"
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
     * @Annotations\Route(path="users/{id}/categories")
     *
     * @Security("is_granted('category_list', fetchedUser)")
     *
     * @Annotations\View()
     *
     * @param User $fetchedUser
     *
     * @return UserCategoryCollection
     */
    public function getCategoriesAction(User $fetchedUser): UserCategoryCollection
    {
        return new UserCategoryCollection(
            $this->getDoctrine()->getRepository('AppBundle:Category')
                ->findBy(['user' => $fetchedUser], ['name' => 'ASC']),
            $fetchedUser
        );
    }

    /**
     * Creates a new category from the submitted data.
     *
     * @ApiDoc(
     *     input = {
     *         "class" = "AppBundle\Form\Type\CategoryFormType",
     *         "name" = ""
     *     },
     *     requirements = {
     *         {
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "ID of the user for which category is created"
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
     *         201 = "Returned when a new category is created",
     *         400 = "Returned when the form has errors",
     *         401 = "Returned when unauthorized",
     *         403 = "Returned when not permitted",
     *         404 = "Returned when user is not found"
     *     }
     * )
     *
     * @Annotations\Route(path="users/{id}/categories")
     *
     * @Security("is_granted('category_create', fetchedUser)")
     *
     * @Annotations\View()
     *
     * @param Request $request
     * @param User    $fetchedUser
     *
     * @return View|Form
     */
    public function postCategoryAction(Request $request, User $fetchedUser)
    {
        $categoryManager = $this->get('app.manager.category_manager');
        $category = $categoryManager->createCategory();
        $category->setUser($fetchedUser);

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->submit(json_decode($request->getContent(), true));

        if ($form->isValid()) {
            $categoryManager->saveAndFlush($category);

            return $this->routeRedirectView('api_get_category', [
                'id' => $category->getId(),
            ]);
        }

        return $form;
    }
}