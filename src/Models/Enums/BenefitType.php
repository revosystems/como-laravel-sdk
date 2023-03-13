<?php

namespace Revo\ComoSdk\Models\Enums;

enum BenefitType: string
{
    case DISCOUNT = 'discount';
    case ITEM_CODE = 'itemCode';
    case DEAL_CODE = 'dealCode';
}
