<?php
declare(strict_types=1);

namespace App\Tests\Form\DataTransformer;

use App\Entity\User;
use App\Form\DataTransformer\UserRoleArrayToStringTransformer;
use PHPUnit\Framework\TestCase;

final class UserRoleArrayToStringTransformerTest extends TestCase
{
    public function test_implements()
    {
        $dataTransformer = new UserRoleArrayToStringTransformer();

        $user = new User();
        $user->setRoles(['ROLE_USER']);

        $dataTransformer->transform($user);
        static::assertSame('ROLE_USER', $user->getRoles());

        $dataTransformer->reverseTransform($user);
        static::assertSame(['ROLE_USER'], $user->getRoles());
    }
}
