<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserIterface;

/**
 * UserInterface
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
interface UserInterface extends BaseUserIterface
{
    /**
     * @return string
     */
    public function getPlainPassword(): string;
}