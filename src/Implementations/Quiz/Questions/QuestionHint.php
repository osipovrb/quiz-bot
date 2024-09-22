<?php

namespace App\Implementations\Quiz\Questions;

class QuestionHint
{
    /**
     * @var int[]
     */
    private readonly array $revealIndices;

    public function __construct(private readonly string $answer)
    {
        $this->revealIndices = $this->generateRevealIndices();
    }

    /**
     * @return int[]
     */
    private function generateRevealIndices(): array
    {
        // indices of answer string
        $lettersIndices = range(0, mb_strlen($this->answer) - 1);

        // remove space indices: there are no sense in revealing spaces
        preg_match_all('/\s/u', $this->answer, $matches, PREG_OFFSET_CAPTURE);
        $spaceIndices = array_map(fn($match) => $match[1], $matches[0] ?? []);
        $revealIndices = array_diff($lettersIndices, $spaceIndices);

        shuffle($revealIndices);

        return $revealIndices;
    }

    public function getAnswerMask(int $revealPercentage): string
    {
        // calculate letters count to reveal
        $answerLength = $this->getAnswerLength();
        $revealCount = intval(round($answerLength * $revealPercentage / 100));

        // generate mask
        $mask = preg_replace('/\S/u', '_', $this->answer);

        // get reveal indices
        $revealIndices = array_slice($this->revealIndices, 0, $revealCount);

        // reveal letters
        $answerArr =
            preg_split('//u', $this->answer, null, PREG_SPLIT_NO_EMPTY);
        $maskArr = preg_split('//u', $mask, null, PREG_SPLIT_NO_EMPTY);
        foreach ($revealIndices as $revealIndex) {
            $maskArr[$revealIndex] = $answerArr[$revealIndex];
        }

        return implode($maskArr);
    }
    
    public function getAnswerLength(): int
    {
        return mb_strlen($this->answer);
    }
}