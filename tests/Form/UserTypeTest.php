<?php
declare(strict_types=1);

namespace App\Tests\Form;

use App\Entity\User;
use App\Form\DataTransformer\UserRoleArrayToStringTransformer;
use App\Form\UserType;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;

final class UserTypeTest extends TypeTestCase
{
    private $dataTransformer;

    protected function setUp()
    {
        $this->dataTransformer = $this->createMock(UserRoleArrayToStringTransformer::class);

        parent::setUp();
    }

    protected function getExtensions()
    {
        $type = new UserType($this->dataTransformer);

        return [
            new PreloadedExtension([$type], [])
        ];
    }

    public function testImplements()
    {
        $type = new UserType(
            $this->createMock(UserRoleArrayToStringTransformer::class)
        );

        static::assertInstanceOf(AbstractType::class, $type);
    }

    public function testSubmitValidData()
    {
        $data = [
            'username' => 'toto',
            'password' => [
                'first' => 'azerty',
                'second' => 'azerty'
            ],
            'email' => 'userTest@maill.com',
            'roles' => 'ROLE_ADMIN'
        ];

        $type = $this->factory->create(UserType::class);
        $type->submit($data);

        $this->assertTrue($type->isSynchronized());
        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertSame($data['username'], $type->get('username')->getData());
        static::assertSame($data['password']['first'], $type->get('password')->getData());
    }
}
