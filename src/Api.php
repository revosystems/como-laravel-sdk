<?php

namespace Revo\ComoSdk;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Revo\ComoSdk\Exceptions\ComoException;

class Api
{
    const ERROR = 'error';

    public function __construct(
        protected string $apiKey,
        protected string $posId,
        protected string $branchId,
        protected string $sourceName,
        protected string $sourceType,
        protected string $sourceVersion,
    ){}

    public function quickRegister(string $phone, ?string $code = null): Response
    {
        return $this->post('registration/quick', [
            'customer' => [
                'phoneNumber' => $phone,
            ],
            'quickRegistrationCode' => $code,
        ]);
    }

    protected function post(string $endpoint, array $params, array $headers = []): Response
    {
        $response = Http::withHeaders([...$this->defaultHeaders(), ...$headers])
            ->post(rtrim($this->url(), '/') . '/' . ltrim($endpoint, '/'), $params)
            ->throw();
        
        if($response->json('status') === static::ERROR) {
            $errors = $response->json('errors');
            throw new ComoException($errors[0]['code'] . ': ' . $errors[0]['message']);
        }

        return $response;
    }

    protected function defaultHeaders(): array
    {
        return [
            'Content-Type' => 'application/json',
            'X-Api-Key' => $this->apiKey,
            'X-Branch-id' => $this->branchId,
            'X-Pos-id' => $this->posId,
            'X-Source-Type' => $this->sourceType,
            'X-Source-Name' => $this->sourceName,
            'X-Source-Version' => $this->sourceVersion,
        ];
    }

    protected function url(): string
    {
        return 'https://api.prod.bcomo.com/api/v4/advanced/';
    }
}