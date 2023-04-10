<?php

/*
 * Copyright Iain Cambridge 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: TBD ( 3 years after 1.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Dto\Request\App\Subscription;

use App\Validator\Constraints\PaymentDetailsExists;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePaymentMethod
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[PaymentDetailsExists]
    #[SerializedName('payment_details')]
    private $paymentDetails;

    /**
     * @return mixed
     */
    public function getPaymentDetails()
    {
        return $this->paymentDetails;
    }

    /**
     * @param mixed $paymentDetails
     */
    public function setPaymentDetails($paymentDetails): void
    {
        $this->paymentDetails = $paymentDetails;
    }
}
