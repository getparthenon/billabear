<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Dto\Response\App\Settings;

use Symfony\Component\Serializer\Annotation\SerializedName;

class NotificationSettingsView
{
    #[SerializedName('notification_settings')]
    private NotificationSettings $notificationSettings;

    #[SerializedName('emsp_choices')]
    private array $emspChoices;

    public function getNotificationSettings(): NotificationSettings
    {
        return $this->notificationSettings;
    }

    public function setNotificationSettings(NotificationSettings $notificationSettings): void
    {
        $this->notificationSettings = $notificationSettings;
    }

    public function getEmspChoices(): array
    {
        return $this->emspChoices;
    }

    public function setEmspChoices(array $emspChoices): void
    {
        $this->emspChoices = $emspChoices;
    }
}
