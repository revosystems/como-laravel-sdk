<?php

namespace Revo\ComoSdk\Tests\Unit\Models;

use Carbon\Carbon;
use Revo\ComoSdk\Models\Enums\OrderType;
use Revo\ComoSdk\Models\MeanOfPayment;
use Revo\ComoSdk\Models\Purchase;
use Revo\ComoSdk\Models\PurchaseLine;

it('can be converted into an array', function() {
    Carbon::setTestNow(Carbon::parse('2023-03-07 10:00:00'));

    $purchase = new Purchase(
        openTime: now(),
        totalAmount: 2000,
        orderType: OrderType::DINE_IN,
        items: collect([new PurchaseLine(
            lineId: 'some-line-id',
            code: 'code',
            name: 'line name',
            departmentCode: 'department-code',
            departmentName: 'department name',
            quantity: 1,
            grossAmount: 2000,
            netAmount: 2000,
        )]),
        employee: 'test',
        totalGeneralDiscount: 1000,
        transactionId: 'some-cool-uuid',
        relatedTransaction: 'some-not-cool-uuid',
        meansOfPayment: collect([new MeanOfPayment('type', 1000)]),
    );

    $this->assertEquals([
        'openTime' => '2023-03-07T10:00:00Z',
        'totalAmount' => 2000,
        'orderType' => 'dineIn',
        'items' => [
            [
                'lineId' => 'some-line-id',
                'code' => 'code',
                'name' => 'line name',
                'departmentCode' => 'department-code',
                'departmentName' => 'department name',
                'quantity' => 1,
                'grossAmount' => 2000,
                'netAmount' => 2000,
            ],
        ],
        'employee' => 'test',
        'totalGeneralDiscount' => 1000,
        'transactionId' => 'some-cool-uuid',
        'relatedTransaction' => 'some-not-cool-uuid',
        'meansOfPayment' => [
            [
                'type' => 'type',
                'amount' => 1000,
            ]
        ],
    ], $purchase->toArray());
});

it('excludes empty values when converting into an array', function() {
    Carbon::setTestNow(Carbon::parse('2023-03-07 10:00:00'));

    $purchase = new Purchase(
        openTime: now(),
        totalAmount: 2000,
        orderType: OrderType::DINE_IN,
        items: collect(),
        transactionId: 'some-cool-uuid',
        meansOfPayment: collect(),
    );

    $this->assertEquals([
        'openTime' => '2023-03-07T10:00:00Z',
        'totalAmount' => 2000,
        'orderType' => 'dineIn',
        'transactionId' => 'some-cool-uuid',
    ], $purchase->toArray());
});
