<?php

namespace Revo\ComoSdk\Tests\Features;

use Revo\ComoSdk\Api;
use Revo\ComoSdk\Exceptions\ComoException;
use Illuminate\Support\Facades\Http;

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

    $this->assertNotNull($response);
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