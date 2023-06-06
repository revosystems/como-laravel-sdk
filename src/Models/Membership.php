<?php

namespace Revo\ComoSdk\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\Asset;
use Revo\ComoSdk\Models\Enums\MembershipStatus;

class Membership
{
    public function __construct(
        public ?string $phone,
        public MembershipStatus $status,
        public Carbon $createdOn,
        public Balance $pointsBalance,
        public Balance $creditBalance,
        public Collection $assets,
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $birthday = null,
        public ?string $email = null,
        public ?string $gender = null,
        public bool $allowSms = false,
        public ?string $commonExtId = null,
        public ?string $comoMemberId = null,
        public ?Collection $tags = null,
    ){}

    public static function fromArray(array $membership): static
    {
        return new static(
            phone: $membership['phoneNumber'] ?? null,
            status: MembershipStatus::from($membership['status']),
            createdOn: Carbon::parse($membership['createdOn']),
            pointsBalance: Balance::fromArray($membership['pointsBalance']),
            creditBalance: Balance::fromArray($membership['creditBalance']),
            assets: Asset::manyFromArray($membership['assets']),
            firstName: $membership['firstName'] ?? null,
            lastName: $membership['lastName'] ?? null,
            birthday: $membership['birthday'] ?? null,
            email: $membership['email'] ?? null,
            gender: $membership['gender'] ?? null,
            allowSms: $membership['allowSMS'] ?? false,
            commonExtId: $membership['commonExtId'] ?? null,
            comoMemberId: $membership['comoMemberId'] ?? null,
            tags: isset($membership['tags']) ? collect($membership['tags']) : null,
        );
    }

    public function getTotalBalance(): int
    {
        return $this->pointsBalance->balance->monetary + $this->creditBalance->balance->monetary;
    }
}
