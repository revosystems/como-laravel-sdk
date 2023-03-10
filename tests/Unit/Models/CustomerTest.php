<?php

namespace Revo\ComoSdk\Tests\Unit\Models;

use Revo\ComoSdk\Models\Customer;

it('can be converted into an array', function() {
    $customer = new Customer(
        phoneNumber: '654654654',
        email: 'test@test.test',
        appClientId: 'app-id',
        customIdentifier: 'custom-id',
    );

    $this->assertEquals([
        'phoneNumber' => '654654654',
        'email' => 'test@test.test',
        'appClientId' => 'app-id',
        'customIdentifier' => 'custom-id',
    ], $customer->toArray());
});

it('excludes empty values when converting into an array', function() {
    $customer = new Customer(
        phoneNumber: '654654654',
    );

    $this->assertEquals([
        'phoneNumber' => '654654654',
    ], $customer->toArray());
});
