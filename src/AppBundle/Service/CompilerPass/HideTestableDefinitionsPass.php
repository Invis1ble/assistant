<?php

namespace AppBundle\Service\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * HideTestableDefinitionsPass
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class HideTestableDefinitionsPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('app.private_testable') as $serviceId => $tags) {
            $definition = $container->getDefinition($serviceId);

            if ($definition->isPublic()) {
                $definition->setPublic(false);
            }
        }
    }
}