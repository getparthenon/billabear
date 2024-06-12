<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Dto\Request\Api\Subscription;

use BillaBear\Validator\Constraints\PaymentMethodExists;
use BillaBear\Validator\Constraints\PriceExists;
use BillaBear\Validator\Constraints\SubscriptionPlanExists;
use BillaBear\Validator\Constraints\ValidPrice;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

#[ValidPrice]
class CreateSubscription
{
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[SubscriptionPlanExists]
    #[SerializedName('subscription_plan')]
    private $subscription_plan;

    #[Assert\Type('string')]
    #[PriceExists]
    #[SerializedName('price')]
    private $price;

    #[Assert\Type('string')]
    private $currency;

    #[Assert\Type('card_token')]
    private $cardToken;

    #[Assert\Type('string')]
    #[Assert\Choice(choices: ['week', 'month', 'year'])]
    private $schedule;

    #[PaymentMethodExists]
    #[SerializedName('payment_details')]
    private $paymentDetails;

    #[Assert\Type('integer')]
    #[Assert\Positive]
    #[SerializedName('seat_number')]
    private $seatNumbers = 1;

    public function getSubscriptionPlan()
    {
        return $this->subscription_plan;
    }

    public function setSubscriptionPlan($subscription_plan): void
    {
        $this->subscription_plan = $subscription_plan;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setPrice($price): void
    {
        $this->price = $price;
    }

    public function getPaymentDetails()
    {
        return $this->paymentDetails;
    }

    public function setPaymentDetails($paymentDetails): void
    {
        $this->paymentDetails = $paymentDetails;
    }

    public function getSeatNumbers(): int
    {
        return $this->seatNumbers;
    }

    public function setSeatNumbers(int $seatNumbers): void
    {
        $this->seatNumbers = $seatNumbers;
    }

    public function hasPaymentDetails(): bool
    {
        return isset($this->paymentDetails);
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency): void
    {
        $this->currency = $currency;
    }

    public function getSchedule()
    {
        return $this->schedule;
    }

    public function setSchedule($schedule): void
    {
        $this->schedule = $schedule;
    }

    public function getCardToken()
    {
        return $this->cardToken;
    }

    public function setCardToken($cardToken): void
    {
        $this->cardToken = $cardToken;
    }
}
