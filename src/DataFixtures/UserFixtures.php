<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserFixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setUsername('User'. $i);
            $user->setEmail("user$i@mail.com");
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'password'. $i));
            $user->setRoles(['ROLE_USER']);

            $manager->persist($user);
            $this->addReference($user->getUsername(), $user);
        }

        $user = new User();
        $user->setUsername('root');
        $user->setEmail('root@mail.com');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'root'));
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $this->addReference($user->getUsername(), $user);

        $manager->flush();
    }

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->passwordEncoder = $container->get('security.password_encoder');
    }
}
