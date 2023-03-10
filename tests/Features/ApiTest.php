<?php

namespace Revo\ComoSdk\Tests\Features;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Revo\ComoSdk\Api;
use Revo\ComoSdk\Exceptions\ComoException;
use Revo\ComoSdk\Models\Asset;
use Revo\ComoSdk\Models\Balance;
use Revo\ComoSdk\Models\Customer;
use Revo\ComoSdk\Models\Enums\AssetStatus;
use Revo\ComoSdk\Models\Enums\MembershipStatus;
use Revo\ComoSdk\Models\MemberNotes;
use Revo\ComoSdk\Models\Membership;
use Revo\ComoSdk\Models\Responses\MemberDetailsResponse;

it('can quick register users', function() {
    Http::fake(fn() => Http::response(json_encode([
        'status' => 'ok',
    ])));

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $response = $api->quickRegister('654654654');

    $this->assertTrue($response);
});

it('can get member details', function() {
    Http::fake(fn() => Http::response(File::get(__DIR__.'/fixtures/member-details-response.json')));

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
    Http::fake(fn() => Http::response(json_encode([
        'status' => 'ok',
    ])));

    $api = new Api(
        apiKey: '1',
        posId: '1',
        branchId: 'test',
        sourceName: 'test_app',
        sourceType: 'app',
        sourceVersion: '1.0',
    );

    $response = $api->sendIdentificationCode(phone:'654654654');

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

    $api->quickRegister('654654654');
})->throws(ComoException::class, '1111: Some sneaky error.');
