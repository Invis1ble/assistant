<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

use AppBundle\Entity\Task;

/**
 * TaskPeriodVoter
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class TaskPeriodVoter extends Voter
{
    const LIST = 'period_list';
    const CREATE = 'period_create';

    /**
     * Determines if the attribute and subject are supported by this voter.
     *
     * @param string $attribute An attribute
     * @param mixed  $subject   The subject to secure, e.g. an object the user wants to access or any other PHP type
     *
     * @return bool True if the attribute and subject are supported, false otherwise
     */
    protected function supports($attribute, $subject)
    {
        $attribute = strtolower($attribute);

        if (!in_array($attribute, [
            self::LIST,
            self::CREATE,
        ])) {
            return false;
        }

        if (!$subject instanceof Task) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string         $attribute
     * @param Task           $task
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $task, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        $username = $user->getUsername();

        $ownerUsername = $task->getCategory()
            ->getUser()
            ->getUsername()
        ;

        switch ($attribute) {
            case self::LIST:
                if ($username === $ownerUsername) {
                    return true;
                }

                break;

            case self::CREATE:
                if ($username === $ownerUsername) {
                    return true;
                }

                break;
        }

        return false;
    }
}