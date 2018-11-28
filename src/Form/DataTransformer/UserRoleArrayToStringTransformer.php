<?php
declare(strict_types=1);

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class UserRoleArrayToStringTransformer.
 *
 * @package App\Form\DataTransformer
 */
final class UserRoleArrayToStringTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     * @return mixed
     */
    public function transform($value)
    {
        $value->setRoles(
            current($value->getRoles())
        );

        return $value;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    public function reverseTransform($value)
    {
        $value->setRoles(
            [$value->getRoles()]
        );

        return $value;
    }
}
