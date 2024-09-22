<?php

namespace App\Implementations\Quiz\Questions;

use App\Contracts\DatabaseInterface;
use App\Exceptions\QuizException;

class QuestionsSeeder
{
    public function __construct(
        private readonly DatabaseInterface $db,
    ) {
    }

    /**
     * @throws QuizException
     */
    public function seed(): void
    {
        $this->db->execute(
            'CREATE TABLE IF NOT EXISTS questions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            answer TEXT NOT NULL
        );'
        );

        [$result] = $this->db->fetch("SELECT COUNT(*) AS count FROM questions");

        if (intval($result['count']) === 0) {
            // prepare query
            $query = 'INSERT INTO questions (title, answer) VALUES(?, ?)';
            // parsing seeds file
            if (!$seeds = file_get_contents(__DIR__ . '/QuestionsSeeds.txt')) {
                throw new QuizException('Failed to read question seeds');
            }
            // preparing questions for transaction
            $questions = array_map(
                fn($row) => explode('|', $row, 2),
                explode(PHP_EOL, $seeds)
            );
            // inserting questions into database
            $this->db->transaction($query, $questions);
        }
    }

}
