<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2025.
 *
 * Use of this software is governed by the Fair Core License, Version 1.0, ALv2 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Command;

use BillaBear\Customer\ObolRegister;
use BillaBear\Repository\CustomerRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'billabear:stripe:sync-customers', description: 'Sync customer data with stripe')]
class StripeSyncCustomersCommand extends Command
{
    public function __construct(private CustomerRepositoryInterface $customerRepository, private ObolRegister $obolRegister)
    {
        parent::__construct(null);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Start syncing customers');

        $lastKey = null;
        do {
            $customers = $this->customerRepository->getList(lastId: $lastKey);
            foreach ($customers->getResults() as $customer) {
                $this->obolRegister->update($customer);
            }
            $lastKey = $customers->getLastKey();
        } while (0 != count($customers->getResults()));

        return Command::SUCCESS;
    }
}
