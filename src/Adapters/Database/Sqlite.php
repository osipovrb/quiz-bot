<?php

namespace App\Adapters\Database;

use App\Contracts\ConfigInterface;

class Sqlite extends Pdo
{
    public function __construct(private readonly ConfigInterface $cfg)
    {
    }

    public function connect(?string $connectionString = null): void
    {
        $file = $this->cfg->get('SQLITE_FILE');
        parent::pdoConnect("sqlite:$file");
    }
}
