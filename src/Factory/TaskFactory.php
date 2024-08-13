<?php

namespace App\Factory;

use App\Entity\Task;
use App\Enum\TaskStatus;
use App\Factory\ProjectFactory;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Task>
 */
final class TaskFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Task::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'project' => ProjectFactory::random(),
            //'project_id' => self::faker()->randomElement(),
            'status' => self::faker()->randomElement(TaskStatus::cases()),
            'title' => self::faker()->text(30),
            'content' => self::faker()->text(255),
            'date' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'member' => EmployeeFactory::random(),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Task $task): void {})
        ;
    }
}
