<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class UserVoter implements VoterInterface
{
    /**
     * @param $user
     * @param $subject
     *
     * @return bool
     */
    public function support($user, $subject): bool
    {
        return $user !== 'anon.'
            && get_class($user) === User::class
            && !\is_null($subject)
            && get_class($subject) === Request::class
            && $subject->attributes->get('_route') === 'user_edit';
    }

    /**
     * @param TokenInterface $token
     * @param mixed $subject
     * @param array $attributes
     *
     * @return int
     */
    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        $user = $token->getUser();

        if (!$this->support($user, $subject)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        if ($token->getUser()->getId() === intval($subject->attributes->get('id'))) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
