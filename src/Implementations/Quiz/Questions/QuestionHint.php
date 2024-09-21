<?php

namespace App\Implementations\Quiz\Questions;

use App\Dto\EventPayloads\HintPayload;

class QuestionHint {
    /**
     * @var int[]
     */
    private readonly array $revealIndices;

    function __construct(private readonly string $answer) {
        $this->revealIndices = $this->generateRevealIndices();
    }

    public function getAnswerLength(): int
    {
        return mb_strlen($this->answer);
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
        $answerArr = preg_split('//u', $this->answer, null, PREG_SPLIT_NO_EMPTY);
        $maskArr = preg_split('//u', $mask, null, PREG_SPLIT_NO_EMPTY);
        foreach ($revealIndices as $revealIndex) {
            $maskArr[$revealIndex] = $answerArr[$revealIndex];
        }

        return implode($maskArr);
    }

    /*
     * @return int[] letter indices to be revealed
     */
    private function generateRevealIndices(): array
    {
        $lettersIndices = range(0, mb_strlen($this->answer) - 1);

        // removing space indices from mask indices: there are no sense in revealing spaces
        preg_match_all('/\s/u', $this->answer, $matches, PREG_OFFSET_CAPTURE);
        $spaceIndices = array_map(fn($match) => $match[1], $matches[0] ?? []);
        $revealIndices = array_diff($lettersIndices, $spaceIndices);

        // shuffling in advance just to make it easy to reveal letters later
        shuffle($revealIndices);

        return $revealIndices;
    }
}