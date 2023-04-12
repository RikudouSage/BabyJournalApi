<?php

namespace App\Enum;

enum ActivityType: string
{
    case FeedingBottle = 'feedingBottle';
    case FeedingBreast = 'feedingBreast';
    case FeedingSolid = 'feedingSolid';
    case Diapering = 'diapering';
}
