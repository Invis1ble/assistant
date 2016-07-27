<?php

namespace AppBundle\DataFixtures\Faker\Provider;

use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

use AppBundle\Security\UserInterface;

/**
 * UserProvider
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserProvider
{
    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    /**
     * @param UserInterface $user
     *
     * @return string
     */
    public function encodedPassword(UserInterface $user): string
    {
        return $this->getEncoderFactory()->getEncoder($user)
            ->encodePassword($user->getPlainPassword(), $user->getSalt());
    }

    /**
     * @return EncoderFactoryInterface
     */
    public function getEncoderFactory(): EncoderFactoryInterface
    {
        return $this->encoderFactory;
    }
}