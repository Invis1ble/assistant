<?php

namespace AppBundle\Service\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * TestEnvironmentCompilerPass
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TestEnvironmentCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $privateServices = [
            'app.security.authorization.voter.abstract_voter',
        ];

        foreach ($privateServices as $serviceId) {
            $serviceDefinition = $container->getDefinition($serviceId);

            if (!$serviceDefinition->isPublic()) {
                $serviceDefinition->setPublic(true);
            }
        }
    }
}