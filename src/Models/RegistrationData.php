<?php

namespace Revo\ComoSdk\Models;

use Illuminate\Contracts\Support\Arrayable as ArrayableContract;
use Illuminate\Support\Carbon;
use Revo\ComoSdk\Models\Concerns\Arrayable;

class RegistrationData implements ArrayableContract
{
    use Arrayable;

    public ?string $birthday;
    public ?string $anniversary;
    public ?string $genericDate1;
    public ?string $genericDate2;
    
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?string $phoneNumber = null,
        public ?string $email = null,
        public ?string $govId = null,
        public ?string $memberId = null,
        public ?string $extClubMemberId = null,
        ?Carbon $birthday = null,
        ?Carbon $anniversary = null,
        public ?string $gender = null,
        public ?string $homeBranchID = null,
        public ?string $addressLine1 = null,
        public ?string $addressLine2 = null,
        public ?string $addressHome = null,
        public ?string $addressStreet = null,
        public ?string $addressFloor = null,
        public ?string $addressCity = null,
        public ?string $addressState = null,
        public ?string $addressCountry = null,
        public ?string $addressZipCode = null,
        public ?bool $allowSMS = null,
        public ?bool $allowEmail = null,
        public ?bool $termsOfUse = null,
        public ?string $genericString1 = null,
        public ?string $genericString2 = null,
        public ?string $genericString3 = null,
        public ?string $genericString4 = null,
        public ?string $genericString5 = null,
        public ?int $genericInteger1 = null,
        public ?int $genericInteger2 = null,
        public ?int $genericInteger3 = null,
        public ?bool $genericCheckBox1 = null,
        public ?bool $genericCheckBox2 = null,
        public ?bool $genericCheckBox3 = null,
        ?Carbon $genericDate1 = null,
        ?Carbon $genericDate2 = null,
    ){
        $this->birthday = $birthday?->format('d.m.Y');
        $this->anniversary = $anniversary?->format('d.m.Y');
        $this->genericDate1 = $genericDate1?->format('d.m.Y H:i:s');
        $this->genericDate2 = $genericDate2?->format('d.m.Y H:i:s');
    }
}
