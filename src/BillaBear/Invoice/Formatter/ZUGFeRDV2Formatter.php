<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Invoice\Formatter;

use BillaBear\Entity\Invoice;
use Easybill\ZUGFeRD2\Builder;
use Easybill\ZUGFeRD2\Model\Amount;
use Easybill\ZUGFeRD2\Model\CrossIndustryInvoice;
use Easybill\ZUGFeRD2\Model\DateTime;
use Easybill\ZUGFeRD2\Model\DocumentContextParameter;
use Easybill\ZUGFeRD2\Model\ExchangedDocument;
use Easybill\ZUGFeRD2\Model\ExchangedDocumentContext;
use Easybill\ZUGFeRD2\Model\HeaderTradeAgreement;
use Easybill\ZUGFeRD2\Model\HeaderTradeSettlement;
use Easybill\ZUGFeRD2\Model\SupplyChainTradeTransaction;
use Easybill\ZUGFeRD2\Model\TaxRegistration;
use Easybill\ZUGFeRD2\Model\TradeAddress;
use Easybill\ZUGFeRD2\Model\TradeParty;
use Easybill\ZUGFeRD2\Model\TradeSettlementHeaderMonetarySummation;

class ZUGFeRDV2Formatter implements InvoiceFormatterInterface
{
    public function generate(Invoice $invoice): mixed
    {
        $document = new CrossIndustryInvoice();
        $document->exchangedDocumentContext = new ExchangedDocumentContext();
        $document->exchangedDocumentContext->documentContextParameter = new DocumentContextParameter();
        $document->exchangedDocumentContext->documentContextParameter->id = Builder::GUIDELINE_SPECIFIED_DOCUMENT_CONTEXT_ID_MINIMUM;

        $document->exchangedDocument = new ExchangedDocument();
        $document->exchangedDocument->id = $invoice->getInvoiceNumber();
        $document->exchangedDocument->typeCode = '380';
        $document->exchangedDocument->issueDateTime = DateTime::create(102, $invoice->getCreatedAt()->format(\DateTime::ATOM));

        $document->supplyChainTradeTransaction = new SupplyChainTradeTransaction();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement = new HeaderTradeAgreement();

        $this->buildSeller($invoice, $document);
        $this->buildBuyer($invoice, $document);

        $document->supplyChainTradeTransaction->applicableHeaderTradeSettlement = new HeaderTradeSettlement();
        $document->supplyChainTradeTransaction->applicableHeaderTradeSettlement->currency = 'EUR';
        $document->supplyChainTradeTransaction->applicableHeaderTradeSettlement->specifiedTradeSettlementHeaderMonetarySummation = $monetarySummation = new TradeSettlementHeaderMonetarySummation();

        $currency = $invoice->getTotalMoney()->getCurrency()->getCurrencyCode();

        $monetarySummation->taxBasisTotalAmount[] = Amount::create((string) $invoice->getSubTotalMoney()->getAmount(), $currency);
        $monetarySummation->taxTotalAmount[] = Amount::create((string) $invoice->getVatTotalMoney()->getAmount(), $currency);
        $monetarySummation->grandTotalAmount[] = Amount::create((string) $invoice->getTotalMoney()->getAmount(), $currency);
        $monetarySummation->duePayableAmount = Amount::create((string) $invoice->getTotalMoney()->getAmount(), $currency);

        return Builder::create()->transform($document);
    }

    public function filename(Invoice $invoice): string
    {
        return sprintf('invoice-%s.xml', $invoice->getInvoiceNumber());
    }

    private function buildSeller(Invoice $invoice, CrossIndustryInvoice $document): void
    {
        $brand = $invoice->getBrandSettings();

        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty = new TradeParty();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty->name = $brand->getBrandName();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty->postalTradeAddress = new TradeAddress();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty->postalTradeAddress->lineOne = $brand->getAddress()->getStreetLineOne();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty->postalTradeAddress->lineTwo = $brand->getAddress()->getStreetLineTwo();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty->postalTradeAddress->city = $brand->getAddress()->getCity();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty->postalTradeAddress->countryCode = $brand->getAddress()->getCountry();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty->postalTradeAddress->postcode = $brand->getAddress()->getPostcode();
        $taxRegistration = TaxRegistration::create($brand->getTaxNumber(), 'VA');
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->sellerTradeParty->taxRegistrations[] = $taxRegistration;
    }

    private function buildBuyer(Invoice $invoice, CrossIndustryInvoice $document): void
    {
        $customer = $invoice->getCustomer();

        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty = new TradeParty();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty->name = $customer->getName();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty->postalTradeAddress = new TradeAddress();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty->postalTradeAddress->lineOne = $customer->getBillingAddress()->getStreetLineOne();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty->postalTradeAddress->lineTwo = $customer->getBillingAddress()->getStreetLineTwo();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty->postalTradeAddress->city = $customer->getBillingAddress()->getCity();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty->postalTradeAddress->countryCode = $customer->getBillingAddress()->getCountry();
        $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty->postalTradeAddress->postcode = $customer->getBillingAddress()->getPostcode();

        if ($customer->getTaxNumber()) {
            $taxRegistration = TaxRegistration::create($customer->getTaxNumber(), 'VA');
            $document->supplyChainTradeTransaction->applicableHeaderTradeAgreement->buyerTradeParty->taxRegistrations[] = $taxRegistration;
        }
    }
}
