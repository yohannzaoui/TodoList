<?php
namespace App\DataFixtures;

use App\Entity\Task;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

final class TaskFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $references[] = 'root';
        for ($i = 0; $i < 5; $i++) {
            $references[] = 'User'. $i;
        }

        $nbReferences = \count($references) -1;

        for ($i = 0; $i < 15; $i++) {
            $task = new Task();
            $task->setContent('Lorem ipsum dolor sit amet, vel ut cetero consequat, te vix dicant libris, saperet vivendo ut mea. Ius ex eros dolore reformidans. Probatus periculis eos no, vix simul alienum persequeris ut. Cu vis alia veniam laboramus. Sit quis animal voluptaria et, maiorum nominati te nec. 
            
            Cu putent vituperatoribus duo. Nec quidam maiestatis vituperata no, ea qui graeco consequuntur. Ei vis veritus scripserit signiferumque, quaeque tibique pri ne. Usu ad omnium efficiantur. Qui ne vidit nemore consetetur, cum an hinc dolor.' . PHP_EOL);
            $task->setTitle('Tâche '.$i);

            if (rand(0, 1)) {
                $task->toggle();
            }

            if ($i === 0 || $i === 1) { // used to test
                $task->setAuthor($this->getReference($references[1])); // $references[1] => User0
            } elseif ($i === 2 && $i === 3) { // used to test
                $task->setAuthor($this->getReference($references[rand(1, $nbReferences)]));
            } elseif (rand(0, 1)) {
                $task->setAuthor($this->getReference($references[rand(0, $nbReferences)]));
            }

            $manager->persist($task);
        }

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class
        ];
    }
}
