<?php

namespace Revo\ComoSdk\Tests\Unit\Models;

use Carbon\Carbon;
use Revo\ComoSdk\Models\PurchaseLine;

it('can be converted into an array', function() {
    Carbon::setTestNow(Carbon::parse('2023-03-07 10:00:00'));

    $purchaseLine = new PurchaseLine(
        lineId: 'some-line-id',
        code: 'code',
        name: 'line name',
        departmentCode: 'department-code',
        departmentName: 'department name',
        quantity: 1,
        grossAmount: 2000,
        netAmount: 2000,
    );

    $this->assertEquals([
        'lineId' => 'some-line-id',
        'code' => 'code',
        'name' => 'line name',
        'departmentCode' => 'department-code',
        'departmentName' => 'department name',
        'quantity' => 1,
        'grossAmount' => 2000,
        'netAmount' => 2000,
    ], $purchaseLine->toArray());
});

it('can have tags', function () {
    Carbon::setTestNow(Carbon::parse('2023-03-07 10:00:00'));

    $purchaseLine = new PurchaseLine(
        lineId: 'some-line-id',
        code: 'code',
        name: 'line name',
        departmentCode: 'department-code',
        departmentName: 'department name',
        quantity: 1,
        grossAmount: 2000,
        netAmount: 2000,
        tags: ['salty', 'crunchy'],
    );

    $this->assertEquals([
        'lineId' => 'some-line-id',
        'code' => 'code',
        'name' => 'line name',
        'departmentCode' => 'department-code',
        'departmentName' => 'department name',
        'quantity' => 1,
        'grossAmount' => 2000,
        'netAmount' => 2000,
        'tags' => ['salty', 'crunchy'],
    ], $purchaseLine->toArray());
});
