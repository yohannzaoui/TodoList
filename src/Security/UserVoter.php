<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class UserVoter.
 *
 * @package App\Security
 */
final class UserVoter extends Voter
{
    const EDIT = 'edit';

    /**
     * {@inheritdoc}
     */
    public function supports($attribute, $subject): bool
    {
        if (!\in_array($attribute, [self::EDIT])) {
            return false;
        }
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return $this->checkIsSelf($subject, $user);
    }

    /**
     * @param User $userSubject
     * @param User $user
     *
     * @return bool
     */
    public function checkIsSelf(User $userSubject, User $user): bool
    {
        return $userSubject->getId() === $user->getId()
            || \in_array('ROLE_ADMIN', $user->getRoles());
    }
}
