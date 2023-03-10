<?php

namespace Revo\ComoSdk\Models\Enums;

enum OrderType: string
{
    case DINE_IN = 'dineIn';
    case DELIVERY = 'delivery';
    case PICKUP = 'pickup';
}
