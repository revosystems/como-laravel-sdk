<?php

namespace Revo\ComoSdk\Tests\Features;

use Carbon\Carbon;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Revo\ComoSdk\Api;
use Revo\ComoSdk\Exceptions\ComoException;
use Revo\ComoSdk\Models\Asset;
use Revo\ComoSdk\Models\Balance;
use Revo\ComoSdk\Models\Benefit;
use Revo\ComoSdk\Models\Customer;
use Revo\ComoSdk\Models\Deal;
use Revo\ComoSdk\Models\DiscountAllocation;
use Revo\ComoSdk\Models\Enums\AssetStatus;
use Revo\ComoSdk\Models\Enums\BenefitType;
use Revo\ComoSdk\Models\Enums\MembershipStatus;
use Revo\ComoSdk\Models\Enums\OrderType;
use Revo\ComoSdk\Models\ExtendedData;
use Revo\ComoSdk\Models\MemberNotes;
use Revo\ComoSdk\Models\Membership;
use Revo\ComoSdk\Models\Purchase;
use Revo\ComoSdk\Models\RedeemAsset;
use Revo\ComoSdk\Models\RegistrationData;
use Revo\ComoSdk\Models\Responses\GetBenefitsResponse;
use Revo\ComoSdk\Models\Responses\MemberDetailsResponse;
use Revo\ComoSdk\Models\Responses\SubmitPurchaseResponse;

it('can quick register users', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/advanced/registration/quick' => Http::response(['status' => 'ok']),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $response = $api->quickRegister(new Customer(phoneNumber:'654654654'));

    $this->assertTrue($response);

    Http::assertSent(function (Request $request) {
        $this->assertEquals([
            'phoneNumber' => '654654654',
        ], $request['customer']);
        return true;
    });
});

it('can quick register users with full info', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/advanced/registration/quick' => Http::response(['status' => 'ok']),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $response = $api->quickRegister(new Customer(
        phoneNumber:'654654654',
        email: 'test@revo.works',
        firstName: 'Test',
    ));

    $this->assertTrue($response);
    Http::assertSent(function (Request $request) {
        $this->assertEquals([
            'phoneNumber' => '654654654',
            'email' => 'test@revo.works',
            'firstName' => 'Test',
        ], $request['customer']);
        return true;
    });
});

it('can get member details', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/getMemberDetails?returnAssets=active&expand=assets.redeemable' => Http::response(File::get(__DIR__.'/fixtures/member-details-response.json')),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $response = $api->getMemberDetails(new Customer(phoneNumber:'654654654'));

    $this->assertNotNull($response);
    $this->assertInstanceOf(MemberDetailsResponse::class, $response);

    $this->assertCount(1, $response->memberNotes);
    tap($response->memberNotes->first(), function(MemberNotes $notes) {
        $this->assertEquals('Deal of the month: 20% off milkshakes', $notes->content);
        $this->assertEquals('text', $notes->type);
    });
    
    tap($response->membership, function(Membership $membership) {
        $this->assertEquals('2128782328', $membership->phone);
        $this->assertEquals(MembershipStatus::ACTIVE, $membership->status);
        $this->assertEquals('2016-05-19 10:19:08', $membership->createdOn->toDateTimeString());
        tap($membership->pointsBalance, function(Balance $balance) {
            $this->assertEquals(false, $balance->usedByPayment);
            $this->assertEquals(2000, $balance->balance->nonMonetary);
            $this->assertEquals(2000, $balance->balance->monetary);
        });
        tap($membership->creditBalance, function(Balance $balance) {
            $this->assertEquals(true, $balance->usedByPayment);
            $this->assertEquals(1000, $balance->balance->nonMonetary);
            $this->assertEquals(1000, $balance->balance->monetary);
        });

        $this->assertCount(3, $membership->assets);
        tap($membership->assets->first(), function(Asset $asset) {
            $this->assertEquals('60y4KJDxK2zfUrcrir9D3K2OWyvorXpPJADNroNY8', $asset->key);
            $this->assertEquals(' 10% Off - Coffee Only!', $asset->name);
            $this->assertEquals(AssetStatus::ACTIVE, $asset->status);
            $this->assertEquals(true, $asset->redeemable);
            $this->assertEquals('10% Off for coffee products only', $asset->description);
            $this->assertEquals('https://storage-download.googleapis.com/server-prod/images/giftimg.jpg', $asset->image);
            $this->assertEquals('2017-01-05 20:59:59', $asset->validFrom->toDateTimeString());
            $this->assertEquals('2017-08-05 20:59:59', $asset->validUntil->toDateTimeString());
            $this->assertNull($asset->nonRedeemableCause);
        });

        $this->assertEquals('Jane', $membership->firstName);
        $this->assertEquals('Smith', $membership->lastName);
        $this->assertEquals('1995-03-03', $membership->birthday);
        $this->assertEquals('jane@email.com', $membership->email);
        $this->assertEquals('female', $membership->gender);
        $this->assertEquals(true, $membership->allowSms);
        $this->assertEquals('1d722661-0a94-4a36-8dea-ae23e5e3f440', $membership->commonExtId);
        $this->assertNull($membership->comoMemberId);
        $this->assertEquals(["VIP","Vegetarian"], $membership->tags->all());
    });
});

it('can request identification code', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/advanced/sendIdentificationCode' => Http::response(['status' => 'ok']),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $response = $api->sendIdentificationCode(new Customer(phoneNumber:'654654654'));

    $this->assertTrue($response);
});

it('thorws exception on error status', function() {
    Http::fake(fn() => Http::response(json_encode([
        'status' => 'error',
        'errors' => [
            [
                'code' => '1111',
                'message' => 'Some sneaky error.',
            ],
        ],
    ])));

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $api->quickRegister(new Customer('654654654'));
})->throws(ComoException::class, '1111: Some sneaky error.');

it('can get benefits', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/getBenefits?expand=discountByDiscount' => Http::response(File::get(__DIR__.'/fixtures/get-benefits-response.json')),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $customers = collect([new Customer(phoneNumber:'654654654')]);
    $purchase = new Purchase(
        now(),
        1000,
        OrderType::DINE_IN
    );
    $assets = collect();
    $response = $api->getBenefits($customers, $purchase, $assets);

    $this->assertNotNull($response);
    $this->assertInstanceOf(GetBenefitsResponse::class, $response);

    $this->assertCount(1, $response->deals);
    tap($response->deals->first(), function(Deal $deal) {
        $this->assertEquals('4EGtHYXmIGHUR6wgf7PsH09CHt9C4gUYrA9BSVakMA8', $deal->key);
        $this->assertEquals('5% off Deal', $deal->name);
        $this->assertCount(1, $deal->benefits);

        tap($deal->benefits->first(), function (Benefit $benefit) {
            $this->assertEquals(BenefitType::DISCOUNT, $benefit->type);
            $this->assertEquals(-60, $benefit->sum);
            $this->assertCount(2, $benefit->extendedData);

            tap($benefit->extendedData->first(), function(ExtendedData $data) {
                $this->assertEquals('1111', $data->item->code);
                $this->assertEquals('sale', $data->item->action);
                $this->assertEquals(5, $data->item->quantity);
                $this->assertEquals(1000, $data->item->netAmount);
                $this->assertEquals('1', $data->item->lineId);

                $this->assertEquals(-50, $data->discount);
                $this->assertEquals(5, $data->discountedQuantity);

                $this->assertCount(1, $data->discountAllocation);

                tap($data->discountAllocation->first(), function(DiscountAllocation $allocation) {
                    $this->assertEquals(5, $allocation->quantity);
                    $this->assertEquals(-10, $allocation->unitDiscount);
                });
            });
        });
    });
    
    $this->assertCount(2, $response->assets);

    tap($response->assets->first(), function(RedeemAsset $asset) {
        $this->assertEquals('30yj439fK2zfUrcrir9D37n3kf8orXpPJADN8fnj56', $asset->key);
        $this->assertNull($asset->code);
        $this->assertEquals('Deal Code', $asset->name);
        $this->assertTrue($asset->redeemable);
        $this->assertCount(1, $asset->benefits);

        tap($asset->benefits->first(), function (Benefit $benefit) {
            $this->assertEquals(BenefitType::DEAL_CODE, $benefit->type);
            $this->assertEquals('65430', $benefit->code);
            $this->assertNull($benefit->sum);
            $this->assertCount(0, $benefit->extendedData);
        });
    });

    tap($response->assets->last(), function(RedeemAsset $asset) {
        $this->assertEquals('2DmlFX3eGFnMP6QYd63dEUF2ptsMPm6i2hNHfrA8', $asset->key);
        $this->assertEquals('27722', $asset->code);
        $this->assertEquals('10% off - coffee only', $asset->name);
        $this->assertTrue($asset->redeemable);
        $this->assertCount(1, $asset->benefits);

        tap($asset->benefits->first(), function (Benefit $benefit) {
            $this->assertEquals(BenefitType::DISCOUNT, $benefit->type);
            $this->assertEquals(-100, $benefit->sum);
            $this->assertCount(1, $benefit->extendedData);
        });
    });

    $this->assertEquals(-160, $response->totalDiscountsSum);
});

it('can cancel purchase', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/cancelPurchase' => Http::response(['status' => 'ok']),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $response = $api->cancelPurchase(confirmation: 'test_confirmation');

    $this->assertTrue($response);
});

it('can void purchase', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/voidPurchase' => Http::response(['status' => 'ok']),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $purchase = new Purchase(
        now(),
        1000,
        OrderType::DINE_IN
    );

    $response = $api->voidPurchase(purchase: $purchase);

    $this->assertTrue($response);
});

it('can submit purchase', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/submitPurchase' => Http::response(['status' => 'ok', 'confirmation' => 'test_confirmation']),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $purchase = new Purchase(
        now(),
        1000,
        OrderType::DINE_IN
    );

    $response = $api->submitPurchase(
        purchase: $purchase,
        customers: collect([new Customer(phoneNumber:'654654654')]),
        purchaseAssets: collect(),
        purchaseDeals: collect(),
        closed: true,
    );

    $this->assertNotNull($response);
    $this->assertInstanceOf(SubmitPurchaseResponse::class, $response);

    $this->assertEquals('test_confirmation', $response->confirmation);
    $this->assertCount(0, $response->memberNotes);
});

it('can submit purchase without customer', function() {
    Carbon::setTestNow(Carbon::parse('2023-03-13 10:00:00'));
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/submitPurchase' => Http::response(['status' => 'ok', 'confirmation' => 'test_confirmation']),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $purchase = new Purchase(
        now(),
        1000,
        OrderType::DINE_IN
    );

    $api->submitPurchase(
        purchase: $purchase,
        customers: collect(),
        purchaseAssets: collect(),
        purchaseDeals: collect(),
        closed: true,
    );

    Http::assertSent(function (Request $request) {
        tap(json_decode($request->body(), true), function($data) {
            $this->assertNotNull($data['purchase'] ?? null);
            $this->assertEquals('2023-03-13T10:00:00Z', $data['purchase']['openTime']);
            $this->assertEquals('dineIn', $data['purchase']['orderType']);
            $this->assertEquals(1000, $data['purchase']['totalAmount']);
            $this->assertNotNull($data['purchase']['transactionId']);

            $this->assertNull($data['customers'] ?? null);
            $this->assertNull($data['deals'] ?? null);
            $this->assertNull($data['assets'] ?? null);
        });
        return true;
    });
});

it('can update member', function() {
    Http::fake([
        'https://api.prod.bcomo.com/api/v4/advanced/updateMember' => Http::response(['status' => 'ok']),
        '*' => Http::response('', 500),
    ]);

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $data = new RegistrationData(
        allowSMS: true,
        allowEmail: true,
        termsOfUse: true,
    );

    $api->updateMember(
        customer: new Customer(phoneNumber:'654654654'),
        data: $data,
    );

    Http::assertSent(function (Request $request) {
        tap(json_decode($request->body(), true), function($data) {
            $this->assertEquals(['phoneNumber' => '654654654'], $data['customer']);
            $this->assertCount(3, $data['registrationData']);
            $this->assertTrue($data['registrationData']['allowSMS']);
            $this->assertTrue($data['registrationData']['allowEmail']);
            $this->assertTrue($data['registrationData']['termsOfUse']);
        });
        return true;
    });
});
