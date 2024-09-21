<?php

namespace App\Enums;

enum EventsEnum: string {
    case START = 'start';
    case QUESTION = 'question';
    case REMAINING_SECONDS = 'remaining_seconds';
    case HINT = 'hint';
    case CORRECT_ANSWER = 'correct_answer';
    case INCORRECT_ANSWER = 'incorrect_answer';
    case QUESTION_TIMED_OUT = 'question_timed_out';
}
