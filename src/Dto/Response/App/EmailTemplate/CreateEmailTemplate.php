<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: 09.10.2026 ( 3 years after 2023.4 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Dto\Response\App\EmailTemplate;

use Symfony\Component\Serializer\Annotation\SerializedName;

class CreateEmailTemplate
{
    #[SerializedName('template_names')]
    private array $templateNames;

    #[SerializedName('brands')]
    private array $brands;

    public function getTemplateNames(): array
    {
        return $this->templateNames;
    }

    public function setTemplateNames(array $templateNames): void
    {
        $this->templateNames = $templateNames;
    }

    public function getBrands(): array
    {
        return $this->brands;
    }

    public function setBrands(array $brands): void
    {
        $this->brands = $brands;
    }
}
