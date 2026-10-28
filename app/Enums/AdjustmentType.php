<?php

namespace App\Enums;

enum AdjustmentType: string
{
    case INCREASE = 'increase';
    case DECREASE = 'decrease';
    case DAMAGED = 'damaged';
    case EXPIRED = 'expired';
}
