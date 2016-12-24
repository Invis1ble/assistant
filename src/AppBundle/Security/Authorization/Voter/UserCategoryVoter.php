<?php

namespace AppBundle\Security\Authorization\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

use AppBundle\Entity\User;

/**
 * UserCategoryVoter
 *
 * @author     Max Invis1ble
 * @copyright  (c) 2016, Max Invis1ble
 * @license    http://www.opensource.org/licenses/mit-license.php MIT
 */
class UserCategoryVoter extends Voter
{
    const LIST = 'category_list';
    const CREATE = 'category_create';

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

        if (!$subject instanceof UserInterface) {
            return false;
        }

        return true;
    }

    /**
     * Perform a single access check operation on a given attribute, subject and token.
     *
     * @param string         $attribute
     * @param User           $subject
     * @param TokenInterface $token
     *
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }
        
        $username = $user->getUsername();
        $subjectUsername = $subject->getUsername();

        switch ($attribute) {
            case self::LIST:
                if ($username === $subjectUsername) {
                    return true;
                }

                break;

            case self::CREATE:
                if ($username === $subjectUsername) {
                    return true;
                }

                break;
        }

        return false;
    }
}