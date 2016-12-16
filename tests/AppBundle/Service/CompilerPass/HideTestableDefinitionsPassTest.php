<?php

namespace AppBundle\Service\CompilerPass;

use PHPUnit_Framework_TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * HideTestableDefinitionsPassTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group unit
 */
class HideTestableDefinitionsPassTest extends PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $container = new ContainerBuilder();

        $container
            ->register('foo')
            ->addTag('app.private_testable')
        ;

        $this->process($container);

        $this->assertFalse(
            $container->getDefinition('foo')->isPublic(),
            'Service should be private'
        );
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function process(ContainerBuilder $container)
    {
        (new HideTestableDefinitionsPass())
            ->process($container);
    }
}