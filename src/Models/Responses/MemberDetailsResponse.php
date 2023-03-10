<?php

namespace Revo\ComoSdk\Models\Responses;

use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\MemberNotes;
use Revo\ComoSdk\Models\Membership;

class MemberDetailsResponse
{
    public Membership $membership;
    public Collection $memberNotes;

    public function __construct(
        array $membership,
        array $memberNotes,
    ){
        $this->membership = Membership::fromArray($membership);
        $this->memberNotes = MemberNotes::manyFromArray($memberNotes);
    }
}
