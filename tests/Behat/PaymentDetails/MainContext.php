<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: DD.MM.2026 ( 3 years after 2024.1 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Tests\Behat\PaymentDetails;

use App\Repository\Orm\CustomerRepository;
use App\Tests\Behat\Customers\CustomerTrait;
use App\Tests\Behat\SendRequestTrait;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Parthenon\Billing\Repository\Orm\PaymentCardServiceRepository;

class MainContext implements Context
{
    use SendRequestTrait;
    use CustomerTrait;
    use PaymentDetailsTrait;

    public function __construct(
        private Session $session,
        private PaymentCardServiceRepository $paymentDetailsRepository,
        protected CustomerRepository $customerRepository
    ) {
    }

    /**
     * @Then the payment details :arg1 for :arg2 should be deleted
     */
    public function thePaymentDetailsForShouldBeDeleted($name, $email)
    {
        $customer = $this->getCustomerByEmail($email);
        $paymentDetails = $this->findPaymentMethod($customer, $name);

        if (!$paymentDetails->isDeleted()) {
            throw new \Exception('Is not deleted');
        }
    }

    /**
     * @Then I will see the payment details in the list with the last four :arg1
     */
    public function iWillSeeThePaymentDetailsInTheListWithTheLastFour($lastFour)
    {
        $data = $this->getJsonContent();

        foreach ($data['data'] as $paymentDetails) {
            if ($paymentDetails['last_four'] == $lastFour) {
                return;
            }
        }

        throw new \Exception("Can't see details");
    }

    /**
     * @Then the payment details :arg1 for :arg2 should be default
     */
    public function thePaymentDetailsShouldBeDefault($name, $email)
    {
        $customer = $this->getCustomerByEmail($email);
        $paymentDetails = $this->findPaymentMethod($customer, $name);

        if (!$paymentDetails->isDefaultPaymentOption()) {
            throw new \Exception('Is not default');
        }
    }

    /**
     * @Then the payment details :arg1 for :arg2 should not be default
     */
    public function thePaymentDetailsCardTwoShouldNotBeDefault($name, $email)
    {
        $customer = $this->getCustomerByEmail($email);
        $paymentDetails = $this->findPaymentMethod($customer, $name);

        if ($paymentDetails->isDefaultPaymentOption()) {
            throw new \Exception('Is default');
        }
    }
}
