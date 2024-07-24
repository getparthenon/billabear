<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear;

use BillaBear\DependencyInjection\Compiler\WorkflowPass;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    public const VERSION = '2024.01.03';
    public const VERSION_ID = '20240103';

    protected function build(\Symfony\Component\DependencyInjection\ContainerBuilder $container): void
    {
        $container->addCompilerPass(new WorkflowPass());
    }
}
