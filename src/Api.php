<?php

namespace Revo\ComoSdk;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Revo\ComoSdk\Exceptions\ComoException;
use Revo\ComoSdk\Models\Customer;
use Revo\ComoSdk\Models\MemberNotes;
use Revo\ComoSdk\Models\Purchase;
use Revo\ComoSdk\Models\Responses\GetBenefitsResponse;
use Revo\ComoSdk\Models\Responses\MemberDetailsResponse;
use Revo\ComoSdk\Models\Responses\SubmitPurchaseResponse;

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

    public function quickRegister(string $phone, ?string $code = null): bool
    {
        $this->post('advanced/registration/quick', [
            'customer' => (new Customer(phoneNumber: $phone))->toArray(),
            'quickRegistrationCode' => $code,
        ]);

        return true;
    }

    public function getMemberDetails(Customer $customer, ?Purchase $purchase = null): MemberDetailsResponse
    {
        $response = $this->post('getMemberDetails?returnAssets=active&expand=assets.redeemable', [
            'customer' => $customer->toArray(),
            ...($purchase ? ['purchase' => $purchase->toArray()] : []),
        ]);

        return new MemberDetailsResponse(
            $response->json('membership'),
            $response->json('memberNotes', []),
        );
    }

    public function sendIdentificationCode(string $phone): bool
    {
        $this->post('advanced/sendIdentificationCode', [
            'customer' => (new Customer(phoneNumber: $phone))->toArray(),
        ]);

        return true;
    }

    public function getBenefits(
        Collection $customers,
        Purchase $purchase,
        Collection $assets
    ): GetBenefitsResponse {
        $response = $this->post('getBenefits?expand=discountByDiscount', [
            'customers' => $customers->toArray(),
            'purchase' => $purchase->toArray(),
            'redeemAssets' => $assets->toArray(),
        ]);

        return new GetBenefitsResponse(
            $response->json('deals', []),
            $response->json('redeemAssets', []),
            $response->json('totalDiscountsSum')
        );
    }

    public function submitPurchase(
        Purchase $purchase,
        Collection $customers,
        Collection $assets,
        Collection $deals,
        bool $closed,
    ): SubmitPurchaseResponse {

        $append = $closed ? '' : '?status=open';
        $response = $this->post("submitPurchase{$append}", [
            ...($customers->isEmpty() ? [] : ['customers' => $customers->toArray()]),
            'purchase' => $purchase->toArray(),
            ...($deals->isEmpty() ? [] : ['deals' => $deals->toArray()]),
            ...($assets->isEmpty() ? [] : ['redeemAssets' => $assets->toArray()]),
        ]);

        return new SubmitPurchaseResponse(
            confirmation: $response->json('confirmation'),
            memberNotes: $response->json('memeberNotes', []),
        );
    }

    public function voidPurchase(Purchase $purchase): bool
    {
        $this->post('voidPurchase', [
            'purchase' => $purchase->toArray(),
        ]);

        return true;
    }

    public function cancelPurchase(string $confirmation): bool
    {
        $this->post('cancelPurchase', [
            'confirmation' => $confirmation,
        ]);
        return true;
    }

    // START PROTECTED METHODS
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
        return 'https://api.prod.bcomo.com/api/v4/';
    }
}
