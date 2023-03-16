<?php

namespace Revo\ComoSdk\Models\Responses;

use Illuminate\Support\Collection;
use Revo\ComoSdk\Models\MemberNotes;

class SubmitPurchaseResponse
{
    public Collection $memberNotes;

    public function __construct(
        public string $confirmation,
        array $memberNotes,
    ){
        $this->memberNotes = MemberNotes::manyFromArray($memberNotes);
    }
}
