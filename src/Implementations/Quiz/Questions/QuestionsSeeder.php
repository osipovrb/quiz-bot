<?php

namespace App\Implementations\Quiz\Questions;

use App\Contracts\DatabaseInterface;
use App\Contracts\Quiz\Questions\QuestionsRepositoryInterface;
use App\Contracts\Quiz\Questions\QuestionsSeederInterface;

class QuestionsSeeder implements QuestionsSeederInterface
{
    public function __construct(
        private readonly DatabaseInterface $db,
        private readonly QuestionsRepositoryInterface $repo
    ) {}

    public function seed(): void
    {
        $this->db->execute('CREATE TABLE IF NOT EXISTS questions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            answer TEXT NOT NULL
        );');

        if ($this->repo->getQuestionsCount() === 0) {
            // query
            $query = 'INSERT INTO questions (title, answer) VALUES(?, ?)';
            // parsing questions file
            $fileContents = file_get_contents(__DIR__ . '/QuestionsSeeds.txt');
            $questions = array_map(
                fn($row) => explode('|', $row, 2),
                explode(PHP_EOL, $fileContents)
            );
            // inserting questions into database
            $this->db->transaction($query, $questions);
        }
    }

}
