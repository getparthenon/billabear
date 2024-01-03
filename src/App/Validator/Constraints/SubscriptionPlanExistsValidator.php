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

use Parthenon\Billing\Repository\SubscriptionPlanRepositoryInterface;
use Parthenon\Common\Exception\NoEntityFoundException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SubscriptionPlanExistsValidator extends ConstraintValidator
{
    public function __construct(private SubscriptionPlanRepositoryInterface $subscriptionPlanRepository)
    {
    }

    public function validate(mixed $value, Constraint $constraint)
    {
        if (empty($value)) {
            return;
        }

        if (Uuid::isValid($value)) {
            try {
                $this->subscriptionPlanRepository->getById($value);

                return;
            } catch (NoEntityFoundException $exception) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        } else {
            try {
                $this->subscriptionPlanRepository->getByCodeName($value);

                return;
            } catch (NoEntityFoundException $exception) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        }
    }
}
