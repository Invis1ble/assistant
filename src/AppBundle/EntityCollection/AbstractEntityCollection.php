<?php

namespace AppBundle\EntityCollection;

/**
 * AbstractEntityCollection
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
abstract class AbstractEntityCollection
{
    /**
     * @var object[]
     */
    protected $entities;

    /**
     * @return object[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param object[] $entities
     *
     * @return $this
     */
    public function setEntities(array $entities)
    {
        $this->entities = $entities;

        return $this;
    }
}