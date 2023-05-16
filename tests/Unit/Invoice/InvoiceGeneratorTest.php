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

namespace App\Tests\Unit\Invoice;

use App\Entity\Customer;
use App\Invoice\InvoiceGenerator;
use App\Invoice\InvoiceNumberGeneratorInterface;
use App\Invoice\PriceInfo;
use App\Invoice\Pricer;
use Brick\Money\Money;
use Monolog\Test\TestCase;
use Parthenon\Billing\Entity\Price;
use Parthenon\Billing\Entity\Subscription;

class InvoiceGeneratorTest extends TestCase
{
    public function testCreateInvoiceFromSubscriptions()
    {
        $mockPrice = $this->createMock(Price::class);

        $subscriptionOne = $this->createMock(Subscription::class);
        $subscriptionOne->method('getPrice')->willReturn($mockPrice);
        $subscriptionOne->method('getPlanName')->willReturn('Plan Name One');

        $subscriptionTwo = $this->createMock(Subscription::class);
        $subscriptionTwo->method('getPrice')->willReturn($mockPrice);
        $subscriptionTwo->method('getPlanName')->willReturn('Plan Name Two');

        $customer = $this->createMock(Customer::class);

        $invoiceNumberGenerator = $this->createMock(InvoiceNumberGeneratorInterface::class);
        $invoiceNumberGenerator->method('generate')->willReturn('D7-848484');

        $pricer = $this->createMock(Pricer::class);

        $priceInfoOne = new PriceInfo(Money::ofMinor(1000, 'USD'), Money::ofMinor(800, 'USD'), Money::ofMinor(200, 'USD'), 20.0);
        $priceInfoTwo = new PriceInfo(Money::ofMinor(4000, 'USD'), Money::ofMinor(3200, 'USD'), Money::ofMinor(800, 'USD'), 20.0);

        $pricer->method('getCustomerPriceInfo')->willReturnOnConsecutiveCalls($priceInfoOne, $priceInfoTwo);

        $subject = new InvoiceGenerator($pricer, $invoiceNumberGenerator);
        $actual = $subject->generateForCustomerAndSubscriptions($customer, [$subscriptionOne, $subscriptionTwo]);

        $this->assertCount(2, $actual->getLines());
        $this->assertEquals(5000, $actual->getTotal());
        $this->assertEquals(4000, $actual->getSubTotal());
        $this->assertEquals(1000, $actual->getVatTotal());
    }
}
