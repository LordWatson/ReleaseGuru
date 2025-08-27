<?php

namespace App\Enums;

enum FeatureTypeEnum: string
{
    case Feature = 'feature';
    case BugFix = 'bug';
    case ChangeRequest = 'change';
}
