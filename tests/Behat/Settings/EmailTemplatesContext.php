<?php

/*
 * Copyright Iain Cambridge 2023.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 *
 * Change Date: TBD ( 3 years after 1.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Tests\Behat\Settings;

use App\Entity\EmailTemplate;
use App\Repository\Orm\EmailTemplateRepository;
use App\Tests\Behat\SendRequestTrait;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;

class EmailTemplatesContext implements Context
{
    use SendRequestTrait;

    public function __construct(
        private Session $session,
        private EmailTemplateRepository $templateRepository,
    ) {
    }

    /**
     * @When I create an email template:
     */
    public function iCreateAnEmailTemplate(TableNode $table)
    {
        $data = $table->getRowsHash();
        $payload = [
            'name' => $data['Name'],
            'locale' => $data['Locale'],
            'subject' => $data['Subject'] ?? null,
            'template_body' => $data['Template Body'] ?? null,
            'template_id' => $data['Template ID'] ?? null,
            'use_emsp_template' => ('true' === strtolower($data['Use Emsp Template'] ?? 'false')),
        ];

        $this->sendJsonRequest('POST', '/app/settings/email-template', $payload);
    }

    /**
     * @Then there will be an email template for :arg1 with locale :arg2
     */
    public function thereWillBeAnEmailTemplateForWithLocale($templateName, $locale)
    {
        $this->getEmailTemplate($templateName, $locale);
    }

    /**
     * @Then there will not be an email template for :arg1 with locale :arg2
     */
    public function thereWillNotBeAnEmailTemplateForWithLocale($templateName, $locale)
    {
        try {
            $this->getEmailTemplate($templateName, $locale);
        } catch (\Throwable $exception) {
            return;
        }
        throw new \Exception('Found');
    }

    public function getEmailTemplate($templateName, $locale): void
    {
        $emailTemplate = $this->templateRepository->findOneBy(['name' => $templateName, 'locale' => $locale]);

        if (!$emailTemplate instanceof EmailTemplate) {
            throw new \Exception('No template found');
        }
    }
}
