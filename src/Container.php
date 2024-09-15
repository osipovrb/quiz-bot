<?php

namespace App;

use Exception;

class Container
{
    private $bindings = [];
    private $instances = [];

    public function bind(string $name, callable $resolver): void
    {
        $this->bindings[$name] = $resolver;
    }

    public function get(string $name): mixed
    {
        if (isset($this->instances[$name])) {
            return $this->instances[$name];
        }

        if (!isset($this->bindings[$name])) {
            throw new Exception("Service $name not found in container");
        }

        $this->instances[$name] = $this->bindings[$name]($this);

        return $this->instances[$name];
    }
}
