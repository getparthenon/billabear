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

namespace App\Background\UpdateChecker;

use App\Kernel;
use App\Repository\SettingsRepositoryInterface;
use Http\Discovery\Psr18ClientDiscovery;
use Nyholm\Psr7\Request;
use Stripe\Account;
use Stripe\Balance;
use Stripe\Payout;
use Stripe\Stripe;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class UpdateChecker
{
    public function __construct(
        private SettingsRepositoryInterface $settingsRepository,
        #[Autowire('%parthenon_billing_payments_obol_config%')]
        private $stripeConfig,
    ) {
    }

    public function execute(): void
    {// Data collected for license enforcement.
        Stripe::setApiKey($this->stripeConfig['api_key']);
        $balance = Balance::retrieve();
        $account = Account::retrieve();
        $dateTime = new \DateTime('-45 days');
        $payoutsData = Payout::all(['limit' => 35, 'created' => ['gt' => $dateTime->getTimestamp()]]);

        $totalAmount = 0;

        /** @var Payout $payout */
        foreach ($payoutsData->data as $payout) {
            $totalAmount += $payout->amount;
        }
        $balancePending = 0;
        foreach ($balance->pending as $pending) {
            $balancePending += $pending->amount;
        }

        $payload = [
            'stripe_account_id' => $account->id,
            'stripe_dashboard_display_name' => $account->settings->dashboard->display_name,
            'stripe_statement_descriptor' => $account->settings->card_payments->statement_descriptor_prefix,
            'stripe_account_type' => $account->type,
            'stripe_payout' => $totalAmount,
            'stripe_currency' => $account->default_currency,
            'stripe_country' => $account->country,
            'stripe_balance' => $balancePending,
            'stripe_livemode' => $balance->livemode,
        ];

        if (isset($account->email)) {
            $payload['stripe_owner_email'] = $account->email;
        }

        if (isset($account->business_profile)) {
            $payload['stripe_owner_name'] = $account->business_profile->name;
            $payload['stripe_support_email'] = $account->business_profile->support_email;
        }
        $settings = $this->settingsRepository->getDefaultSettings();
        $payload['url'] = $settings->getSystemSettings()->getSystemUrl();
        $payload['id'] = $settings->getId();
        $payload['version'] = Kernel::VERSION;

        $request = new Request('POST', 'https://announce.billabear.com/update', headers: ['Content-Type' => 'application/json'], body: json_encode($payload));

        $client = Psr18ClientDiscovery::find();
        $response = $client->sendRequest($request);
        $data = json_decode($response->getBody()->getContents(), true);

        if (str_contains('-dev', Kernel::VERSION)) {
            return;
        }

        if (version_compare(Kernel::VERSION, $data['version'], '<')) {
            $settings->getSystemSettings()->setUpdateAvailable(true);
        }

        $this->settingsRepository->save($settings);
    }
}
