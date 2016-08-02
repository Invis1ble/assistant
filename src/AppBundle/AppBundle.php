<?php

namespace AppBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

use AppBundle\Service\CompilerPass\TestEnvironmentCompilerPass;

/**
 * AppBundle
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class AppBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        if ($container->getParameter('kernel.environment') === 'test') {
            $container->addCompilerPass(new TestEnvironmentCompilerPass());
        }
    }
}
