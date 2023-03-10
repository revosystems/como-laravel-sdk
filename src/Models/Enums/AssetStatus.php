<?php

namespace Revo\ComoSdk\Models\Enums;

enum AssetStatus: string
{
    case ACTIVE = 'Active';
    case REDEEMED = 'Redeemed';
    case DEACTIVATED = 'Deactivated';
    case EXPIRED = 'Expired';
    case FUTURE = 'Future';
    case IN_PROGRESS = 'In Progress';
}
