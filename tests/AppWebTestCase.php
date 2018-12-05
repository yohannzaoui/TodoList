<?php
declare(strict_types=1);

namespace App\Tests;

use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AppWebTestCase extends WebTestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager;

    private function entityManagerInit()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function entityManagerClose()
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null; // avoid memory leaks
    }
    /**
     * @param string $entity
     *
     * @return EntityRepository
     */
    protected function getRepository(string $entity): EntityRepository
    {
        $this->entityManagerInit();

        return $this->entityManager->getRepository($entity);
    }
}
