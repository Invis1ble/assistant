<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

use AppBundle\Entity\Category;

/**
 * CategoryTaskVoter
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryTaskVoter extends Voter
{
    const LIST = 'task_list';
    const CREATE = 'task_create';

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

        if (!$subject instanceof Category) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string         $attribute
     * @param Category       $category
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $category, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }
        
        $username = $user->getUsername();
        
        $categoryOwnerUsername = $category->getUser()
            ->getUsername()
        ;

        switch ($attribute) {
            case self::LIST:
                if ($username === $categoryOwnerUsername) {
                    return true;
                }

                break;

            case self::CREATE:
                if ($username === $categoryOwnerUsername) {
                    return true;
                }

                break;
        }

        return false;
    }
}