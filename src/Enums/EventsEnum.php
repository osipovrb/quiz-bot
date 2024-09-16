<?php

namespace App\Enums;

enum EventsEnum: string {
    case START = 'start';
    case CORRECT_ANSWER = 'correct_answer';
    case INCORRECT_ANSWER = 'incorrect_answer';
    case QUESTION = 'question';
    case HINT = 'hint';
    case REMAINING_SECONDS = 'remaining_seconds';
    case QUESTION_TIMED_OUT = 'question_timed_out';
}
