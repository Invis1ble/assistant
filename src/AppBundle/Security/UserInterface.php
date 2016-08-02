<?php

namespace AppBundle\Security;

use Symfony\Component\Security\Core\User\UserInterface as BaseUserInterface;

/**
 * UserInterface
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
interface UserInterface extends BaseUserInterface
{
    /**
     * @return string|null
     */
    public function getPlainPassword();

    /**
     * @param string $password
     *
     * @return UserInterface
     */
    public function setPassword(string $password);
}