# como-laravel-sdk

Laravel SDK for Como

## Installation

First add this repository to the composer:

```json
    { "type": "vcs", "url": "https://github.com/revosystems/como-laravel-sdk" }
```

then you can require it via composer:

```bash
    composer require revosystems/como-laravel-sdk
```

## Usage

In order to make call to the Como API you should init the \Revo\ComoSdk\Api with the following parameters:

```php
$api = new Api(
    string $apiKey,
    string $posId,
    string $branchId,
    string $sourceName,
    string $sourceType,
    string $sourceVersion,
)
```

All the api methods throw \Revo\ComoSdk\Exceptions\ComoException on an error response from Como's API. (200 response with error status on the body)
The error code and message can be found on the Exceptiuon message:

```php
try{
    [...]
} catch (\Revo\ComoSdk\Exceptions\ComoException $e) {
    Log::error($e->getMessage()); // [error_code]: [error_message]
}
```

An \Illuminate\Http\Client\RequestException is also thrown on a 400 or 500 response.

You can call the quick register endpoint with this method:

```php
$response = $api->quickRegister(
    \Revo\ComoSdk\Models\Customer $customer,
    ?string $code = null,
)
```
