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

namespace App\Enum;

enum SubscriptionSeatModificationType: string
{
    case ADDED = 'added';
    case REMOVED = 'removed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromName(?string $name): self
    {
        if (!$name) {
            return self::ADDED;
        }
        foreach (self::cases() as $status) {
            if ($name === $status->value) {
                return $status;
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum ".self::class);
    }
}
