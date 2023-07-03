<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\TaskMeta;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $task = new Task();
        $taskMeta = new TaskMeta();
        $task->setName("A+B");
        $task->setDescription("<p>Given A, B. You need to find A + B.</p>");
        $task->setPublished(true);
        $task->setTimeLimit(1000);
        $task->setMemoryLimit(1024);

        $taskMeta->setTask($task);
        $taskMeta->setAuthor(1);
        $taskMeta->setCreatedAt(new \DateTimeImmutable());

        $task->setTaskMeta($taskMeta);

        $manager->persist($task);

        $manager->flush();
    }
}
