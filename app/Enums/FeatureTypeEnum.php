<?php

namespace App\Enums;

enum FeatureTypeEnum: string
{
    case Feature = 'feature';
    case BugFix = 'bug fix';
    case ChangeRequest = 'change request';
}
