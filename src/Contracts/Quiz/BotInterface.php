<?php

namespace App\Contracts\Quiz;

use App\Contracts\ConfigInterface;
use App\Contracts\Quiz\Questions\QuestionsRepositoryInterface;
use App\Contracts\IpcInterface;

interface BotInterface
{
  function __construct(
    QuestionsRepositoryInterface $repo, 
    IpcInterface $ipc,
    ConfigInterface $config,
  );
  public function start();
}