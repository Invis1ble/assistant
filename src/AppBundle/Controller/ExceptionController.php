<?php


namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use FOS\RestBundle\Controller\ExceptionController as BaseExceptionController;

/**
 * ExceptionController
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class ExceptionController extends BaseExceptionController
{
    /**
     * {@inheritdoc}
     */
    protected function createView(\Exception $exception, $code, array $templateData, Request $request, $showException)
    {
        $view = parent::createView($exception, $code, $templateData, $request, $showException);
        $view->setFormat('json');

        return $view;
    }
}