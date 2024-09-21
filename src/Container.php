<?php

namespace App;

use App\Exceptions\ContainerException;

class Container
{
    private array $bindings = [];
    private array $instances = [];

    public function bind(string $name, callable $resolver): void
    {
        $this->bindings[$name] = $resolver;
    }

    /**
     * @throws ContainerException
     */
    public function get(string $name): mixed
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (!isset($this->bindings[$name])) {
            throw new ContainerException("Service $name not found in container");
        }

        $this->instances[$name] = $this->bindings[$name]($this);

        return $this->instances[$name];
    }
}
