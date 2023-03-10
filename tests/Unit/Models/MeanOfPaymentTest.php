<?php

namespace Revo\ComoSdk\Tests\Unit\Models;

use Revo\ComoSdk\Models\MeanOfPayment;

it('can be converted into an array', function() {
    $meanOfpayment = new MeanOfPayment(type: 'type', amount: 1000);

    $this->assertEquals([
        'type' => 'type',
        'amount' => 1000,
    ], $meanOfpayment->toArray());
});
