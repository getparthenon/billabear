<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Tests\Behat\Products;

use BillaBear\Entity\Product;

trait ProductTrait
{
    public function getProductByName(string $name): Product
    {
        $product = $this->productRepository->findOneBy(['name' => $name]);

        if (!$product instanceof Product) {
            throw new \Exception('No product found');
        }

        $this->productRepository->getEntityManager()->refresh($product);

        return $product;
    }
}
