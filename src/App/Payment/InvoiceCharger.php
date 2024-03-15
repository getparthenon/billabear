<?php

/*
 * Copyright Humbly Arrogant Software Limited 2022-2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: DD.MM.2027 ( 3 years after 2024.1 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Payment;

use App\Entity\Invoice;
use App\Entity\Payment;
use App\Event\InvoicePaid;
use App\Repository\InvoiceRepositoryInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Obol\Model\Charge;
use Obol\Provider\ProviderInterface;
use Parthenon\Billing\Entity\PaymentCard;
use Parthenon\Billing\Event\PaymentCreated;
use Parthenon\Billing\Obol\BillingDetailsFactoryInterface;
use Parthenon\Billing\Obol\PaymentFactoryInterface;
use Parthenon\Billing\Repository\PaymentCardRepositoryInterface;
use Parthenon\Billing\Repository\PaymentRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InvoiceCharger
{
    public function __construct(
        private ProviderInterface $provider,
        private PaymentCardRepositoryInterface $paymentCardRepository,
        private BillingDetailsFactoryInterface $billingDetailsFactory,
        private PaymentFactoryInterface $paymentFactory,
        private PaymentRepositoryInterface $paymentRepository,
        private EventDispatcherInterface $eventDispatcher,
        private InvoiceRepositoryInterface $invoiceRepository,
        private PaymentFailureHandler $paymentFailureHandler,
    ) {
    }

    public function chargeInvoice(Invoice $invoice, ?PaymentCard $paymentCard = null, ?\DateTime $createdAt = null): bool
    {
        if (!$paymentCard) {
            $paymentCard = $this->paymentCardRepository->getDefaultPaymentCardForCustomer($invoice->getCustomer());
        }
        $billingDetails = $this->billingDetailsFactory->createFromCustomerAndPaymentDetails($invoice->getCustomer(), $paymentCard);

        $charge = new Charge();
        $charge->setName('BillaBear');
        $charge->setAmount($invoice->getTotalMoney());
        $charge->setBillingDetails($billingDetails);

        $response = $this->provider->payments()->chargeCardOnFile($charge);

        if (!$response->isSuccessful()) {
            $this->paymentFailureHandler->handleInvoiceAndResponse($invoice, $response);

            return false;
        }

        /** @var Payment $payment */
        $payment = $this->paymentFactory->fromSubscriptionCreation($response->getPaymentDetails(), $invoice->getCustomer());

        if ($createdAt) {
            $payment->setCreatedAt($createdAt);
            $invoice->setPaidAt($createdAt);
        } else {
            $invoice->setPaidAt(new \DateTime('now'));
        }

        foreach ($invoice->getSubscriptions() as $subscription) {
            $payment->addSubscription($subscription);
        }
        $payment->setInvoice($invoice);

        $this->paymentRepository->save($payment);
        $invoice->setPayments(new ArrayCollection([$payment]));
        $invoice->setPaid(true);
        $this->invoiceRepository->save($invoice);

        $this->eventDispatcher->dispatch(new InvoicePaid($invoice), InvoicePaid::NAME);
        $this->eventDispatcher->dispatch(new PaymentCreated($payment, true), PaymentCreated::NAME);

        return true;
    }
}
