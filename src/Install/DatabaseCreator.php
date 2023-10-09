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

namespace App\Install;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Parthenon\MultiTenancy\Database\MigrationsHandler;
use Parthenon\MultiTenancy\Entity\TenantInterface;

class DatabaseCreator
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ManagerRegistry $managerRegistry,
        private string $kernelProjectDir,
    ) {
    }

    public function createDbSchema()
    {
        $em = $this->entityManager;
        $metaData = $em->getMetadataFactory()->getAllMetadata();

        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $tool->createSchema($metaData);
        $tenant = $this->getTenant();
        $migrationsHandler = new MigrationsHandler($this->managerRegistry, $this->kernelProjectDir.'/migrations', 'default');
        $migrationsHandler->handleMigrations($tenant);
    }

    public function getTenant()
    {
        return new class() implements TenantInterface {
            public function getId()
            {
                // TODO: Implement getId() method.
            }

            public function setId($id): void
            {
                // TODO: Implement setId() method.
            }

            public function getCreatedAt(): \DateTime
            {
                // TODO: Implement getCreatedAt() method.
            }

            public function setCreatedAt(\DateTime $createdAt): void
            {
                // TODO: Implement setCreatedAt() method.
            }

            public function getUpdatedAt(): ?\DateTime
            {
                // TODO: Implement getUpdatedAt() method.
            }

            public function setUpdatedAt(?\DateTime $updatedAt): void
            {
                // TODO: Implement setUpdatedAt() method.
            }

            public function getDeletedAt(): ?\DateTime
            {
                // TODO: Implement getDeletedAt() method.
            }

            public function setDeletedAt(?\DateTime $deletedAt): void
            {
                // TODO: Implement setDeletedAt() method.
            }

            public function getSubdomain(): string
            {
                // TODO: Implement getSubdomain() method.
            }

            public function setSubdomain(string $subdomain): void
            {
                // TODO: Implement setSubdomain() method.
            }

            public function getDatabase(): string
            {
                // TODO: Implement getDatabase() method.
            }

            public function setDatabase(string $database): void
            {
                // TODO: Implement setDatabase() method.
            }
        };
    }
}
