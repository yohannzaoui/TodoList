<?php
declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class UserVoter implements VoterInterface
{
    const ROUTES =  [
        'user_edit',
        'task_edit',
        'task_delete',
    ];

    /**
     * @param $user
     * @param $subject
     * @param array $attributes
     *
     * @return bool
     */
    public function support($user, $subject, array $attributes): bool
    {
        return $user !== 'anon.'
            && get_class($user) === User::class
            && \in_array('ROLE_ADMIN', $attributes)
            && null !== $subject
            && get_class($subject) === Request::class
            && \in_array($subject->attributes->get('_route'), self::ROUTES);
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

        if (!$this->support($user, $subject, $attributes)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        $route = $subject->attributes->get('_route');
        $routeId = (int) $subject->attributes->get('id');


        $result = false;

        if (preg_match('/task_/', $route)) {
            $result = ($user->getTasks()->exists(function ($key, $task) use ($routeId) {
                return $task->getId() === $routeId ?? null; // Checks if tasks's id and route id are equals
            }));
        }
        elseif (preg_match('/user_/', $route)) {
            $result = $user->getId() === $routeId;
        }


        if ($result) {
            return VoterInterface::ACCESS_GRANTED;
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}
