<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Integrations\Accounting;

interface AccountingIntegrationInterface
{
    public function getInvoiceService(): InvoiceInterface;

    public function getVoucherService(): VoucherInterface;

    public function getCustomerService(): CustomerInterface;

    public function getPaymentService(): PaymentInterface;
}
