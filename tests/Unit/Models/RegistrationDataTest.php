<?php

namespace Revo\ComoSdk\Tests\Unit\Models;

use Illuminate\Support\Carbon;
use Revo\ComoSdk\Models\RegistrationData;

it('can be converted into an array', function() {
    $registrationData = new RegistrationData(
        firstName: 'firstName',
        lastName: 'lastName',
        phoneNumber: 'phoneNumber',
        email: 'email',
        govId: 'govId',
        memberId: 'memberId',
        extClubMemberId: 'extClubMemberId',
        birthday: Carbon::parse('29-12-2023 10:00:00'),
        anniversary: Carbon::parse('29-12-2023 10:00:00'),
        gender: 'gender',
        homeBranchID: 'homeBranchID',
        addressLine1: 'addressLine1',
        addressLine2: 'addressLine2',
        addressHome: 'addressHome',
        addressStreet: 'addressStreet',
        addressFloor: 'addressFloor',
        addressCity: 'addressCity',
        addressState: 'addressState',
        addressCountry: 'addressCountry',
        addressZipCode: 'addressZipCode',
        allowSMS: true,
        allowEmail: true,
        termsOfUse: true,
        genericString1: 'genericString1',
        genericString2: 'genericString2',
        genericString3: 'genericString3',
        genericString4: 'genericString4',
        genericString5: 'genericString5',
        genericInteger1: 1,
        genericInteger2: 1,
        genericInteger3: 1,
        genericCheckBox1: true,
        genericCheckBox2: true,
        genericCheckBox3: true,
        genericDate1: Carbon::parse('29-12-2023 10:00:00'),
        genericDate2: Carbon::parse('29-12-2023 10:00:00'),
    );

    $this->assertEquals([
        'firstName' => 'firstName',
        'lastName' => 'lastName',
        'phoneNumber' => 'phoneNumber',
        'email' => 'email',
        'govId' => 'govId',
        'memberId' => 'memberId',
        'extClubMemberId' => 'extClubMemberId',
        'birthday' => '29.12.2023',
        'anniversary' => '29.12.2023',
        'gender' => 'gender',
        'homeBranchID' => 'homeBranchID',
        'addressLine1' => 'addressLine1',
        'addressLine2' => 'addressLine2',
        'addressHome' => 'addressHome',
        'addressStreet' => 'addressStreet',
        'addressFloor' => 'addressFloor',
        'addressCity' => 'addressCity',
        'addressState' => 'addressState',
        'addressCountry' => 'addressCountry',
        'addressZipCode' => 'addressZipCode',
        'allowSMS' => true,
        'allowEmail' => true,
        'termsOfUse' => true,
        'genericString1' => 'genericString1',
        'genericString2' => 'genericString2',
        'genericString3' => 'genericString3',
        'genericString4' => 'genericString4',
        'genericString5' => 'genericString5',
        'genericInteger1' => 1,
        'genericInteger2' => 1,
        'genericInteger3' => 1,
        'genericCheckBox1' => true,
        'genericCheckBox2' => true,
        'genericCheckBox3' => true,
        'genericDate1' => '29.12.2023 10:00:00',
        'genericDate2' => '29.12.2023 10:00:00',
    ], $registrationData->toArray());
});

it('excludes empty values when converting into an array', function() {
    $registrationData = new RegistrationData(
        allowSMS: true,
    );

    $this->assertEquals([
        'allowSMS' => true,
    ], $registrationData->toArray());
});
