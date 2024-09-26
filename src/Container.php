<?php

namespace App;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    /**
     * @var array<string, callable>
     */
    private array $bindings = [];

    /**
     * @var array<string, object>
     */
    private array $instances = [];

    public function bind(string $id, callable $resolver): void
    {
        $this->bindings[$id] = $resolver;
    }

    /**
     * @throws NotFoundExceptionInterface
     */
    public function get(string $id): mixed
    {
        if (isset($this->instances[$id])) {
            return $this->instances[$id];
        }

        if (!$this->has($id)) {
            throw new class extends \Exception
                implements NotFoundExceptionInterface {
            };
        }

        $this->instances[$id] = $this->bindings[$id]($this);

        return $this->bindings[$id];
    }

    public function has(string $id): bool
    {
        return isset($this->instances[$id]);
    }
}
