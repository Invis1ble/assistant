<?php

namespace Tests\AppBundle\Security\Authorization\Voter;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;

/**
 * AbstractVoterTestCase
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
abstract class AbstractVoterTestCase extends KernelTestCase
{
    /**
     * @var Voter
     */
    protected $voter;

    /**
     * @return Voter
     */
    protected function getVoter(): Voter
    {
        return $this->voter;
    }

    public function setUp()
    {
        static::bootKernel();

        $this->populateVariables();
    }

    /**
     * @param TokenInterface $token
     * @param mixed          $subject
     * @param string         $attribute
     * @param int            $decision
     *
     * @dataProvider voteProvider
     */
    public function testVote(TokenInterface $token, $subject, string $attribute, int $decision)
    {
        $this->assertSame(
            $this->getVoter()->vote($token, $subject, [$attribute]),
            $decision
        );
    }

    /**
     * @param UserInterface $user
     *
     * @return JWTUserToken
     */
    protected function createJWTToken(UserInterface $user): JWTUserToken
    {
        $token = new JWTUserToken($user->getRoles());
        $token->setUser($user);

        return $token;
    }

    /**
     * @return AnonymousToken
     */
    protected function createAnonymousToken(): AnonymousToken
    {
        return new AnonymousToken('foo', 'anonymous');
    }

    abstract protected function populateVariables();

    /**
     * @return array[]
     */
    abstract public function voteProvider(): array;
}