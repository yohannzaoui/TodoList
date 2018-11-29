<?php
declare(strict_types=1);

namespace App\Tests\Form;

use App\Form\LoginType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Test\TypeTestCase;

final class LoginTypeTest extends TypeTestCase
{
    public function testImplements()
    {
        $type = new LoginType();

        static::assertInstanceOf(AbstractType::class, $type);
    }

    public function testSubmitValidData()
    {
        $data = [
            '_username' => 'toto',
            '_password' => 'azerty'
        ];

        $type = $this->factory->create(LoginType::class);
        $type->submit($data);

        static::assertTrue($type->isValid());
        static::assertTrue($type->isSubmitted());
        static::assertSame($data['_username'], $type->get('_username')->getData());
        static::assertSame($data['_password'], $type->get('_password')->getData());
    }
}
