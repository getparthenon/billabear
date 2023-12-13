<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: DD.MM.2026 ( 3 years after 2024.1 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Dto\Request\App\Settings;

use App\Entity\Settings\NotificationSettings as Entity;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[Assert\Callback('validate')]
class NotificationSettings
{
    #[Assert\Type('boolean')]
    #[SerializedName('send_customer_notifications')]
    private ?bool $sendCustomerNotifications;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: Entity::EMSP_CHOICES)]
    private ?string $emsp;

    #[Assert\NotBlank(allowNull: true)]
    #[SerializedName('emsp_api_key')]
    private ?string $emspApiKey;

    #[Assert\NotBlank(allowNull: true)]
    #[SerializedName('emsp_api_url')]
    private ?string $emspApiUrl;

    #[Assert\NotBlank(allowNull: true)]
    #[SerializedName('emsp_domain')]
    private ?string $emspDomain;

    #[Assert\NotBlank()]
    #[Assert\Email]
    #[SerializedName('default_outgoing_email')]
    private ?string $defaultOutgoingEmail;

    public function getSendCustomerNotifications(): ?bool
    {
        return $this->sendCustomerNotifications;
    }

    public function setSendCustomerNotifications(?bool $sendCustomerNotifications): void
    {
        $this->sendCustomerNotifications = $sendCustomerNotifications;
    }

    public function getEmsp(): ?string
    {
        return $this->emsp;
    }

    public function setEmsp(?string $emsp): void
    {
        $this->emsp = $emsp;
    }

    public function getEmspApiKey(): ?string
    {
        return $this->emspApiKey;
    }

    public function setEmspApiKey(?string $emspApiKey): void
    {
        $this->emspApiKey = $emspApiKey;
    }

    public function getEmspApiUrl(): ?string
    {
        return $this->emspApiUrl;
    }

    public function setEmspApiUrl(?string $emspApiUrl): void
    {
        $this->emspApiUrl = $emspApiUrl;
    }

    public function getEmspDomain(): ?string
    {
        return $this->emspDomain;
    }

    public function setEmspDomain(?string $emspDomain): void
    {
        $this->emspDomain = $emspDomain;
    }

    public function getDefaultOutgoingEmail(): ?string
    {
        return $this->defaultOutgoingEmail;
    }

    public function setDefaultOutgoingEmail(?string $defaultOutgoingEmail): void
    {
        $this->defaultOutgoingEmail = $defaultOutgoingEmail;
    }

    public function validate(ExecutionContextInterface $context, $payload)
    {
        if (empty($this->emsp) || \App\Entity\Settings\NotificationSettings::EMSP_SYSTEM === $this->emsp) {
            return;
        }

        if (empty($this->emspApiKey)) {
            $context->buildViolation('must have an api key')->atPath('emsp_api_key')->addViolation();
        }
        if (\App\Entity\Settings\NotificationSettings::EMSP_MAILGUN === $this->emsp) {
            if (empty($this->emspApiUrl)) {
                $context->buildViolation('must have api url')->atPath('emsp_api_url')->addViolation();
            }
            if (empty($this->emspDomain)) {
                $context->buildViolation('must have domain')->atPath('emsp_domain')->addViolation();
            }
        }
    }
}
