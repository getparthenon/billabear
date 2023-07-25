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

namespace App\Tests\Behat\Products;

use App\Enum\TaxType;
use App\Repository\Orm\ProductRepository;
use App\Tests\Behat\SendRequestTrait;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;

class AppContext implements Context
{
    use SendRequestTrait;
    use ProductTrait;

    public function __construct(
        private Session $session,
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * @When I create a product via the app with the following info
     */
    public function iCreateAProductViaTheAppWithTheFollowingInfo(TableNode $table)
    {
        $data = $table->getRowsHash();
        $payload = [
            'name' => $data['Name'],
        ];

        if (isset($data['Tax Type'])) {
            $payload['tax_type'] = match ($data['Tax Type']) {
                'Digital Services' => TaxType::DIGITAL_SERVICES->value,
                'Physical' => TaxType::PHYSICAL->value,
                default => TaxType::DIGITAL_GOODS->value,
            };
        }

        if (isset($data['Tax Rate'])) {
            $payload['tax_rate'] = floatval($data['Tax Rate']);
        }

        $this->sendJsonRequest('POST', '/app/product', $payload);
    }

    /**
     * @Then the product :arg1 should have the tax rate :arg2
     */
    public function theProductShouldHaveTheTaxRate($name, $arg2)
    {
        $product = $this->getProductByName($name);

        if ($product->getTaxRate() != $arg2) {
            throw new \Exception(sprintf('Expected %s but got %s', $arg2, $product->getTaxRate()));
        }
    }

    /**
     * @When I use the APP to list product
     */
    public function iUseTheAppToListProduct()
    {
        $this->sendJsonRequest('GET', '/app/product');
    }

    /**
     * @When I use the APP to view product :arg1
     */
    public function iUseTheAppToViewProduct($name)
    {
        $product = $this->getProductByName($name);

        $this->sendJsonRequest('GET', '/app/product/'.$product->getId());
    }

    /**
     * @When I update the product info via the APP for :arg1:
     */
    public function iUpdateTheProductInfoViaTheAppFor($name, TableNode $table)
    {
        $product = $this->getProductByName($name);

        $data = $table->getRowsHash();
        $payload = [
            'name' => $data['Name'],
        ];
        if (isset($data['Tax Type'])) {
            $payload['tax_type'] = match ($data['Tax Type']) {
                'Digital Services' => TaxType::DIGITAL_SERVICES->value,
                'Physical' => TaxType::PHYSICAL->value,
                default => TaxType::DIGITAL_GOODS->value,
            };
        }

        if (isset($data['Tax Rate'])) {
            $payload['tax_rate'] = floatval($data['Tax Rate']);
        }

        $this->sendJsonRequest('POST', '/app/product/'.$product->getId(), $payload);
    }
}
