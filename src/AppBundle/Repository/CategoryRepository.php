<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016-2017, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryRepository extends EntityRepository
{
    /**
     *
     *
     * @return string
     */
    public function getRootAlias()
    {
        return 'category';
    }
}