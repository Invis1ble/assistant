<?php

namespace AppBundle\EntityCollection;

use AppBundle\Entity\{
    Category,
    Task
};

/**
 * CategoryTaskCollection
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryTaskCollection extends AbstractEntityCollection
{
    /**
     * @var Category
     */
    protected $category;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var int
     */
    protected $limit;

    /**
     * TaskCollection constructor.
     *
     * @param Task[]   $entities
     * @param Category     $category
     * @param int|null $offset
     * @param int|null $limit
     */
    public function __construct(array $entities = [], Category $category, int $offset = null, int $limit = null)
    {
        $this
            ->setEntities($entities)
            ->setCategory($category)
            ->setLimit($limit)
            ->setOffset($offset)
        ;
    }

    /**
     * @return Category
     */
    public function getCategory(): Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     *
     * @return CategoryTaskCollection
     */
    public function setCategory(Category $category): CategoryTaskCollection
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @param int|null $offset
     *
     * @return CategoryTaskCollection
     */
    public function setOffset(int $offset = null): CategoryTaskCollection
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param int|null $limit
     *
     * @return CategoryTaskCollection
     */
    public function setLimit(int $limit = null): CategoryTaskCollection
    {
        $this->limit = $limit;

        return $this;
    }
}