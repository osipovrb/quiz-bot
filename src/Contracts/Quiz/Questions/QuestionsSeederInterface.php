<?php

namespace App\Contracts\Quiz\Questions;

use App\Contracts\DatabaseInterface;
use App\Contracts\Quiz\Questions\QuestionsRepositoryInterface;

interface QuestionsSeederInterface
{
    public function __construct(DatabaseInterface $db, QuestionsRepositoryInterface $repo);
    public function seed();
}
