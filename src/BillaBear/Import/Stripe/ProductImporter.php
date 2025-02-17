<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Import\Stripe;

use BillaBear\DataMappers\ProductDataMapper;
use BillaBear\Entity\StripeImport;
use BillaBear\Entity\SubscriptionPlan;
use BillaBear\Repository\StripeImportRepositoryInterface;
use BillaBear\Repository\TaxTypeRepositoryInterface;
use Obol\Model\Product;
use Obol\Provider\ProviderInterface;
use Parthenon\Billing\Repository\ProductRepositoryInterface;
use Parthenon\Billing\Repository\SubscriptionPlanRepositoryInterface;
use Parthenon\Common\Exception\NoEntityFoundException;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;

#[Autoconfigure(lazy: true)]
class ProductImporter
{
    public function __construct(
        private ProviderInterface $provider,
        private ProductRepositoryInterface $productRepository,
        private SubscriptionPlanRepositoryInterface $subscriptionPlanRepository,
        private StripeImportRepositoryInterface $stripeImportRepository,
        private ProductDataMapper $productFactory,
        private TaxTypeRepositoryInterface $taxTypeRepository,
    ) {
    }

    public function import(StripeImport $stripeImport, bool $save = true)
    {
        $provider = $this->provider;
        $limit = 25;
        $lastId = $save ? $stripeImport->getLastId() : null;
        $defaultTaxType = $this->taxTypeRepository->getDefault();
        do {
            $productList = $provider->products()->list($limit, $lastId);
            /** @var Product $productModel */
            foreach ($productList as $productModel) {
                try {
                    $product = $this->productRepository->getByExternalReference($productModel->getId());
                } catch (NoEntityFoundException $exception) {
                    $product = null;
                }
                $product = $this->productFactory->createFromObol($productModel, $product);
                $product->setTaxType($defaultTaxType);
                $this->productRepository->save($product);

                $subscriptionPlan = new SubscriptionPlan();
                $subscriptionPlan->setName($product->getName());
                $subscriptionPlan->setProduct($product);
                $subscriptionPlan->setPerSeat(false);
                $subscriptionPlan->setFree(false);
                $subscriptionPlan->setHasTrial(false);
                $subscriptionPlan->setUserCount(1);
                $this->subscriptionPlanRepository->save($subscriptionPlan);
                $lastId = $productModel->getId();
            }
            $stripeImport->setLastId($lastId);
            $stripeImport->setUpdatedAt(new \DateTime());
            if ($save) {
                $this->stripeImportRepository->save($stripeImport);
            }
        } while (sizeof($productList) == $limit);
        $stripeImport->setLastId(null);
    }
}
