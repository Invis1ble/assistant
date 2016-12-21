<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Form;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use AppBundle\Entity\Category;
use AppBundle\Form\Type\CategoryFormType;

/**
 * CategoryController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryController extends FOSRestController
{
    /**
     * Get single category
     *
     * @ApiDoc(
     *     resource = true,
     *     statusCodes = {
     *         200 = "Returned when successful",
     *         401 = "Returned when unauthorized",
     *         403 = "Returned when not permitted",
     *         404 = "Returned when the category is not found"
     *     },
     *     headers = {
     *         {
     *             "name" = "Authorization",
     *             "default" = "Bearer ",
     *             "description" = "Authorization JSON Web Token",
     *         }
     *     },
     *     requirements = {
     *         {
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "Category ID"
     *         }
     *     }
     * )
     *
     * @Annotations\Route(path="categories/{id}")
     *
     * @Security("is_granted('show', category)")
     *
     * @Annotations\View()
     *
     * @param Category $category
     *
     * @return Category
     */
    public function getCategoryAction(Category $category): Category
    {
        return $category;
    }

    /**
     * Patch existing category from the submitted data.
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
     *             "description" = "Category ID"
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
     *         404 = "Returned when the category is not found"
     *     }
     * )
     *
     * @Annotations\Route(path="categories/{id}")
     *
     * @Security("is_granted('edit', category)")
     *
     * @Annotations\View()
     *
     * @param Request  $request
     * @param Category $category
     *
     * @return View|Form
     */
    public function patchCategoryAction(Request $request, Category $category)
    {
        $form = $this->createForm(CategoryFormType::class, $category);
        $form->submit(json_decode($request->getContent(), true), false);

        if ($form->isValid()) {
            $this->get('app.manager.category_manager')->saveAndFlush($category);

            return $this->routeRedirectView('api_get_category', [
                'id' => $category->getId(),
            ], Response::HTTP_NO_CONTENT);
        }

        return $form;
    }

    /**
     * Deletes a category.
     *
     * @ApiDoc(
     *     requirements = {
     *         {
     *             "name" = "id",
     *             "dataType" = "UUID string",
     *             "description" = "Category ID"
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
     *         401 = "Returned when unauthorized",
     *         404 = "Returned when the category is not found"
     *     }
     * )
     *
     * @Annotations\Route(path="categories/{id}")
     *
     * @Security("is_granted('delete', category)")
     *
     * @Annotations\View()
     *
     * @param Category $category
     *
     * @return View
     */
    public function deleteCategoryAction(Category $category): View
    {
        $this->get('app.manager.category_manager')->removeAndFlush($category);

        return $this->routeRedirectView('api_get_user_categories', [
            'id' => $category->getUser()->getId(),
        ], Response::HTTP_NO_CONTENT);
    }
}