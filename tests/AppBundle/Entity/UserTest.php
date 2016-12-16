<?php

namespace tests\AppBundle\Entity;

use AppBundle\Entity\User;

/**
 * UserTest
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 *
 * @group unit
 */
class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testRoles()
    {
        $user = new User();

        $this->assertSame([User::ROLE_DEFAULT], $user->getRoles());

        $user->addRole(User::ROLE_DEFAULT);

        $this->assertSame([User::ROLE_DEFAULT], $user->getRoles());

        $user->addRole('ROLE_ADMIN');
        $expectedRoles = [User::ROLE_DEFAULT, 'ROLE_ADMIN'];
        sort($expectedRoles);

        $roles = $user->getRoles();
        sort($roles);

        $this->assertSame($expectedRoles, $roles);

        $user->setRoles([]);

        $this->assertSame([User::ROLE_DEFAULT], $user->getRoles());

        $user->setRoles([User::ROLE_DEFAULT]);

        $this->assertSame([User::ROLE_DEFAULT], $user->getRoles());

        $expectedRoles = [User::ROLE_DEFAULT, 'ROLE_ADMIN'];
        $user->setRoles($expectedRoles);
        sort($expectedRoles);

        $roles = $user->getRoles();
        sort($roles);

        $this->assertSame($expectedRoles, $roles);
        $this->assertTrue($user->hasRole('ROLE_ADMIN'));

        $user->removeRole('ROLE_ADMIN');

        $this->assertFalse($user->hasRole('ROLE_ADMIN'));

        $this->assertTrue($user->hasRole(User::ROLE_DEFAULT));

        $user->removeRole(User::ROLE_DEFAULT);

        $this->assertTrue($user->hasRole(User::ROLE_DEFAULT));
    }
}