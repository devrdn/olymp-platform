<?php

namespace App\Config;

enum SolutionStatus: int {
    case QUEUE  = 0;
    case COMPILE = 1;
    case EVALUATE = 2;
    case DONE = 3;
    case COMPILE_FAIL  = 11;
    case NOT_FOUND = 12;
}