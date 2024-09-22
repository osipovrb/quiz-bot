<?php

namespace App\Implementations\Quiz\Questions;

use App\Contracts\DatabaseInterface;

class QuestionsRepository
{
    public function __construct(private readonly DatabaseInterface $db)
    {
    }

    public function getRandomQuestion(): Question
    {
        [$result] = $this->db->fetch(
            "SELECT title, answer FROM questions ORDER BY RANDOM() LIMIT 1"
        );

        return new Question($result['title'], $result['answer']);
    }

    public function getQuestionsCount(): int
    {
        [$result] = $this->db->fetch('SELECT COUNT(*) as count FROM questions');

        return $result['count'];
    }
}
