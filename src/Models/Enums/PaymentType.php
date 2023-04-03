<?php

namespace Revo\ComoSdk\Models\Enums;

enum PaymentType: string
{
    case MEMBER_CREDIT = 'memberCredit';
    case MEMBER_POINTS = 'memberPoints';
    case CREDIT_CARD = 'creditCard';
}
