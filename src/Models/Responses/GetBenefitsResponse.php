<?php

namespace Revo\ComoSdk\Models\Responses;

use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\Deal;
use Revo\ComoSdk\Models\RedeemAsset;

class GetBenefitsResponse
{
    public Collection $deals;
    public Collection $assets;

    public function __construct(
        array $deals,
        array $assets,
        public ?int $totalDiscountsSum,
    ){
        $this->deals = Deal::manyFromArray($deals);
        $this->assets = RedeemAsset::manyFromArray($assets);
    }
}
