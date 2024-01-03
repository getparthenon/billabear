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

namespace App\Validator\Constraints;

use App\Dto\Request\App\Product\UpdateSubscriptionPlan;
use Parthenon\Billing\Repository\SubscriptionPlanRepositoryInterface;
use Parthenon\Common\Exception\NoEntityFoundException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UpdateUniqueSubscriptionPlanCodeNameValidator extends ConstraintValidator
{
    public function __construct(private SubscriptionPlanRepositoryInterface $subscriptionPlanRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        $id = null;
        if ($value instanceof UpdateSubscriptionPlan) {
            $id = $value->getId();
            $value = $value->getCodeName();
        }

        if (empty($value)) {
            return;
        }
        try {
            $subscriptionPlan = $this->subscriptionPlanRepository->getByCodeName($value);
        } catch (NoEntityFoundException $exception) {
            return;
        }

        if (isset($id)) {
            if (strval($id) === strval($subscriptionPlan->getId())) {
                return;
            }
        }

        $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
    }
}
