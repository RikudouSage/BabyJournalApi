<?php

namespace App\Enum;

enum ParentalUnitSetting: string
{
    case FeedingBreakLength = 'feeding_break_length';
    case ConsiderWaterFeeding = 'consider_water_feeding';
    case CalculateFeedingSince = 'calculate_feeding_since';
    case CalculatePumpingSince = 'calculate_pumping_since';
    case CalculateSleepingSince = 'calculate_sleeping_since';
    case UseSharedInProgress = 'use_shared_in_progress';
}
