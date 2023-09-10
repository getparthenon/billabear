<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: 24.08.2026 ( 3 years after 1.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Dto\Response\Portal\Checkout;

use App\Dto\Response\Portal\Quote\StripeInfo;

class CustomerCreation
{
    protected StripeInfo $stripe;

    private UpdatedCheckoutAmounts $amounts;

    public function getAmounts(): UpdatedCheckoutAmounts
    {
        return $this->amounts;
    }

    public function setAmounts(UpdatedCheckoutAmounts $amounts): void
    {
        $this->amounts = $amounts;
    }

    public function getStripe(): StripeInfo
    {
        return $this->stripe;
    }

    public function setStripe(StripeInfo $stripe): void
    {
        $this->stripe = $stripe;
    }
}
