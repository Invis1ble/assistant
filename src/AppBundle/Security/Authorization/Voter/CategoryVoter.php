<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

use AppBundle\Entity\Category;

/**
 * CategoryVoter
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class CategoryVoter extends Voter
{
    const SHOW = 'show';
    const EDIT = 'edit';
    const DELETE = 'delete';

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
            self::SHOW,
            self::EDIT,
            self::DELETE,
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
        
        $ownerUsername = $category->getUser()
            ->getUsername()
        ;

        switch ($attribute) {
            case self::SHOW:
                if ($username === $ownerUsername) {
                    return true;
                }

                break;

            case self::EDIT:
                if ($username === $ownerUsername) {
                    return true;
                }

                break;

            case self::DELETE:
                if ($username === $ownerUsername) {
                    return true;
                }

                break;
        }

        return false;
    }
}