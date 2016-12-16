<?php

namespace AppBundle\Entity;

use InvalidArgumentException;

/**
 * TokenContainer
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TokenContainer
{
    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $refreshToken;

    /**
     * TokenContainer constructor.
     *
     * @param array $data
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $data)
    {
        if (!isset($data['token'])) {
            throw new InvalidArgumentException('No token.');
        }

        if (!isset($data['refresh_token'])) {
            throw new InvalidArgumentException('No refresh token.');
        }

        if (!is_string($data['token'])) {
            throw new InvalidArgumentException('Expected string, ' . gettype($data['token'] . ' given.'));
        }

        if (!is_string($data['refresh_token'])) {
            throw new InvalidArgumentException('Expected string, ' . gettype($data['token'] . ' given.'));
        }

        $this
            ->setToken($data['token'])
            ->setRefreshToken($data['refresh_token'])
        ;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return TokenContainer
     */
    protected function setToken(string $token): TokenContainer
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     *
     * @return TokenContainer
     */
    protected function setRefreshToken(string $refreshToken): TokenContainer
    {
        $this->refreshToken = $refreshToken;

        return $this;
    }
}