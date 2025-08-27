<?php

namespace App\Enums;

enum TaskTypeEnum: string
{
    case Feature = 'feature';
    case BugFix = 'bug';
    case ChangeRequest = 'change';
}
