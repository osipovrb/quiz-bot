<?php

interface ConfigInterface
{
    public function __constructor(string $path);
    public function get(string $key): string;
}
