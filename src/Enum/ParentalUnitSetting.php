<?php

namespace App\Enum;

enum ParentalUnitSetting: string
{
    case FeedingBreakLength = 'feeding_break_length';
    case CalculateFeedingSince = 'calculate_feeding_since';
    case CalculatePumpingSince = 'calculate_pumping_since';
    case CalculateSleepingSince = 'calculate_sleeping_since';
}
