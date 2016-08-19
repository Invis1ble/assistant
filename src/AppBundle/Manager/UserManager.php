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
     */
    public function save(UserInterface $user)
    {
        $this->updatePassword($user);
        $this->objectManager->persist($user);
    }

    /**
     * @param UserInterface $user
     */
    public function saveAndFlush(UserInterface $user)
    {
        $this->save($user);
        $this->objectManager->flush();
    }

    /**
     * @param UserInterface $user
     */
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