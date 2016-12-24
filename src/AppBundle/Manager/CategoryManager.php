<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Category;

/**
 * CategoryManager
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryManager extends AbstractManager
{
    /**
     * @return Category
     */
    public function createCategory(): Category
    {
        return new Category();
    }

    /**
     * @param Category $category
     */
    public function save(Category $category)
    {
        $this->objectManager->persist($category);
    }

    /**
     * @param Category $category
     */
    public function saveAndFlush(Category $category)
    {
        $this->objectManager->persist($category);
        $this->objectManager->flush();
    }

    /**
     * @param Category $category
     */
    public function remove(Category $category)
    {
        $this->objectManager->remove($category);
    }

    /**
     * @param Category $category
     */
    public function removeAndFlush(Category $category)
    {
        $this->remove($category);
        $this->objectManager->flush();
    }
}