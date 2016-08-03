<?php

namespace AppBundle\Manager;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use AppBundle\Entity\User;
use AppBundle\Security\UserInterface;

/**
 * UserManager
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserManager extends AbstractManager
{
    /**
     * @var UserPasswordEncoderInterface
     */
    protected $userPasswordEncoder;

    /**
     * @return User
     */
    public function createUser(): User
    {
        return new User();
    }

    /**
     * @param UserInterface $user
     * @param bool          $andFlush
     */
    public function save(UserInterface $user, bool $andFlush = true)
    {
        $this->updatePassword($user);
        $this->objectManager->persist($user);

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    public function updatePassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();

        if ('' !== $plainPassword) {
            $user->setPassword($this->getUserPasswordEncoder()->encodePassword($user, $plainPassword));
            $user->eraseCredentials();
        }
    }

    /**
     * @return UserPasswordEncoderInterface
     */
    public function getUserPasswordEncoder(): UserPasswordEncoderInterface
    {
        return $this->userPasswordEncoder;
    }

    /**
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     *
     * @return UserManager
     */
    public function setUserPasswordEncoder(UserPasswordEncoderInterface $userPasswordEncoder): UserManager
    {
        $this->userPasswordEncoder = $userPasswordEncoder;

        return $this;
    }
}