<?php

namespace Tests\AppBundle\Controller\Constraint;

use PHPUnit_Framework_Constraint_Count;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * ResponseItemCount
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class ResponseItemCount extends PHPUnit_Framework_Constraint_Count
{
    /**
     * @var string
     */
    protected $path;

    /**
     * ResponseItemCount constructor.
     *
     * @param string $path
     * @param int    $expected
     */
    public function __construct(string $path, int $expected)
    {
        parent::__construct($expected);

        $this
            ->setPath($path)
        ;
    }

    /**
     * @param Response $response
     *
     * @return int
     */
    protected function getCountOf($response)
    {
        return count(PropertyAccess::createPropertyAccessor()
            ->getValue(json_decode($response->getContent()), $this->getPath()));
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     *
     * @return ResponseItemCount
     */
    public function setPath(string $path): ResponseItemCount
    {
        $this->path = $path;

        return $this;
    }
}