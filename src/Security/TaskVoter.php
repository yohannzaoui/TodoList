<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * Class TaskVoter.
 *
 * @package App\Security
 */
final class TaskVoter extends Voter
{
    const EDIT = 'edit';
    const DELETE = 'delete';

    /**
     * {@inheritdoc}
     */
    public function supports($attribute, $subject): bool
    {
        if (!\in_array($attribute, [self::EDIT, self::DELETE])) {
            return false;
        }

        if (!$subject instanceof Task) {
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

        return $this->canAccess($subject, $user);
    }

    /**
     * @param Task $task
     * @param User $user
     *
     * @return bool
     */
    public function canAccess(Task $task, User $user): bool
    {
        if (\in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        if (\is_string($task->getAuthor())) {
            return false;
        }

        return $task->getAuthor()->getId() === $user->getId();
    }
}
