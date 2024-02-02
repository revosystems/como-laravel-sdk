<?php

namespace Revo\ComoSdk\Enums;

enum ErrorCode: int
{
    case INVALID_VERIFICATION_CODE = 4007004;
    case PENDING_VERIFICATION_CODE = 4007005;
    case PAY_AMOUNT_OVER_LIMIT = 4007009;
    case PAY_AMOUNT_BELOW_LIMIT = 4007010;
    case MISSING_VERIFICATION_CODE = 4007014;

    public static function validationCodeErrorCodes(): array
    {
        return [
            static::INVALID_VERIFICATION_CODE->value,
            static::PENDING_VERIFICATION_CODE->value,
            static::MISSING_VERIFICATION_CODE->value,
        ];
    }
}
