<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\User\Notification;

use BillaBear\Entity\Customer;
use BillaBear\Repository\BrandSettingsRepositoryInterface;
use Parthenon\Common\Config;
use Parthenon\Notification\Email;
use Parthenon\User\Entity\ForgotPasswordCode;
use Parthenon\User\Entity\InviteCode;
use Parthenon\User\Entity\UserInterface;
use Parthenon\User\Notification\MessageFactory as BaseMessageFactory;
use Parthenon\User\Notification\UserEmail;
use Twig\Environment;

class MessageFactory extends BaseMessageFactory
{
    public function __construct(Config $config, private Environment $twig, private BrandSettingsRepositoryInterface $brandSettingsRepository)
    {
        parent::__construct($config);
    }

    public function getPasswordResetMessage(UserInterface $user, ForgotPasswordCode $passwordReset): Email
    {
        $brand = $this->brandSettingsRepository->getByCode(Customer::DEFAULT_BRAND);
        $emailVariables = [
            'forgot_url' => rtrim($this->config->getSiteUrl(), '/').'/forgot-password/'.$passwordReset->getCode(),
            'brand_name' => $brand->getBrandName(),
        ];
        $content = $this->twig->render('Mail/forgot_password.html.twig', $emailVariables);

        $message = UserEmail::createFromUser($user);
        $message->setSubject('Password Reset')
            ->setContent($content);

        return $message;
    }

    public function getInviteMessage(UserInterface $user, InviteCode $inviteCode): Email
    {
        $brand = $this->brandSettingsRepository->getByCode(Customer::DEFAULT_BRAND);
        $emailVariables = [
            'invite_url' => rtrim($this->config->getSiteUrl(), '/').'/signup/'.$inviteCode->getCode(),
            'brand_name' => $brand->getBrandName(),
        ];
        $content = $this->twig->render('Mail/invite.html.twig', $emailVariables);

        $message = UserEmail::createFromUser($user);
        $message->setSubject('Invited to use BillaBear for '.$brand->getBrandName())
            ->setContent($content)
            ->setToName('Invited User')
            ->setToAddress($inviteCode->getEmail());

        return $message;
    }
}
