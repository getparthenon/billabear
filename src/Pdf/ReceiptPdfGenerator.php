<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: DD.MM.2026 ( 3 years after 1.1.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Pdf;

use App\Entity\BrandSettings;
use App\Entity\Customer;
use App\Entity\Template;
use App\Repository\TemplateRepositoryInterface;
use Parthenon\Billing\Entity\Receipt;
use Parthenon\Billing\Entity\ReceiptLine;
use Parthenon\Common\Address;
use Parthenon\Common\Exception\NoEntityFoundException;
use Parthenon\Common\Pdf\GeneratorInterface;
use Twig\Environment;

class ReceiptPdfGenerator
{
    public function __construct(
        private TemplateRepositoryInterface $templateRepository,
        private Environment $twig,
        private GeneratorInterface $pdfGenerator,
    ) {
    }

    public function generate(Receipt $receipt)
    {
        $customer = $receipt->getCustomer();

        if (!$customer instanceof Customer) {
            throw new \LogicException('Invalid customer type');
        }
        try {
            $template = $this->templateRepository->getByNameAndBrand(Template::NAME_RECEIPT, $customer->getBrand());
        } catch (NoEntityFoundException $exception) {
            $template = $this->templateRepository->getByNameAndBrand(Template::NAME_RECEIPT, Customer::DEFAULT_BRAND);
        }

        $twigTemplate = $this->twig->createTemplate($template->getContent());
        $content = $this->twig->render($twigTemplate, $this->getData($receipt));

        return $this->pdfGenerator->generate($content);
    }

    private function getData(Receipt $receipt): array
    {
        $customer = $receipt->getCustomer();
        if (!$customer instanceof Customer) {
            throw new \Exception('Not customer entity');
        }

        return [
            'customer' => $this->getCustomerData($customer),
            'brand' => $this->getBrandData($customer->getBrandSettings()),
            'receipt' => $this->getInvoiceData($receipt),
        ];
    }

    protected function getCustomerData(Customer $customer): array
    {
        return [
            'name' => $customer->getName(),
            'email' => $customer->getBillingEmail(),
        ];
    }

    private function getInvoiceData(Receipt $receipt): array
    {
        return [
            'id' => (string) $receipt->getId(),
            'number' => $receipt->getInvoiceNumber(),
            'total' => $receipt->getTotal(),
            'total_display' => (string) $receipt->getTotalMoney(),
            'sub_total' => $receipt->getSubTotal(),
            'tax_total' => $receipt->getVatTotal(),
            'currency' => $receipt->getCurrency(),
            'lines' => array_map([$this, 'getReceiptLine'], $receipt->getLines()),
            'biller_address' => $this->getAddress($receipt->getBillerAddress()),
            'payee_address' => $this->getAddress($receipt->getPayeeAddress()),
            'created_at' => $receipt->getCreatedAt()->format(\DATE_ATOM),
        ];
    }

    private function getReceiptLine(ReceiptLine $receiptLine): array
    {
        return [
            'total' => $receiptLine->getTotal(),
            'total_display' => (string) $receiptLine->getTotalMoney(),
            'sub_total' => $receiptLine->getSubTotal(),
            'tax_total' => $receiptLine->getVatTotal(),
            'tax_percentage' => $receiptLine->getVatPercentage(),
            'description' => $receiptLine->getDescription(),
        ];
    }

    protected function getBrandData(BrandSettings $brandSettings): array
    {
        return [
            'name' => $brandSettings->getBrandName(),
            'address' => $this->getAddress($brandSettings->getAddress()),
            'tax_number' => $brandSettings->getTaxNumber(),
        ];
    }

    protected function getAddress(Address $address): array
    {
        return [
            'company_name' => $address->getCompanyName(),
            'street_line_one' => $address->getStreetLineOne(),
            'street_line_two' => $address->getStreetLineTwo(),
            'city' => $address->getCity(),
            'region' => $address->getRegion(),
            'country' => $address->getCountry(),
            'postcode' => $address->getPostcode(),
        ];
    }
}
