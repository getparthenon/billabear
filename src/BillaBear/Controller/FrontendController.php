<?php

/*
 * Copyright Humbly Arrogant Software Limited 2023-2024.
 *
 * Use of this software is governed by the Functional Source License, Version 1.1, Apache 2.0 Future License included in the LICENSE.md file and at https://github.com/BillaBear/billabear/blob/main/LICENSE.
 */

namespace BillaBear\Controller;

use BillaBear\Repository\SettingsRepositoryInterface;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Parthenon\MultiTenancy\Exception\NoTenantFoundException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class FrontendController
{
    #[Route('/', name: 'app_index_landing', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    #[Route('/login', name: 'app_public', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    #[Route('/signup', name: 'app_signup', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    #[Route('/signup/{code}', name: 'app_invite', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    #[Route('/forgot-password', name: 'app_forgot_password', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    #[Route('/forgot-password/{code}', name: 'app_forgot_password_confirm', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    #[Route('/confirm-email/{code}', name: 'app_confirm_email', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    #[Route('/site/{vueRouting}', name: 'app_main', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    #[Route('/app/plan', name: 'app_plan', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' !== '%kernel.environment%'")]
    public function home(
        Request $request,
        Environment $twig,
        SettingsRepositoryInterface $settingsRepository,
        LoggerInterface $logger,
        Profiler $profiler,
    ): Response {
        $logger->info('A request was made to the frontend controller');
        try {
            $settings = $settingsRepository->getDefaultSettings();
        } catch (TableNotFoundException $exception) {
            $logger->info('Redirected to install page');

            return new RedirectResponse('/install');
        } catch (NoTenantFoundException $e) {
            $logger->warning('No tenant was found', ['hostname' => $request->getHost()]);

            return new Response($twig->render('not_found.html.twig'));
        }

        return new Response($twig->render('index.html.twig'));
    }

    #[Route('/', name: 'app_index_landing_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    #[Route('/login', name: 'app_public_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    #[Route('/signup', name: 'app_signup_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    #[Route('/signup/{code}', name: 'app_invite_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    #[Route('/forgot-password', name: 'app_forgot_password_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    #[Route('/forgot-password/{code}', name: 'app_forgot_password_confirm_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    #[Route('/confirm-email/{code}', name: 'app_confirm_email_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    #[Route('/site/{vueRouting}', name: 'app_main_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    #[Route('/app/plan', name: 'app_plan_dev', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null], condition: "'dev' === '%kernel.environment%'")]
    public function homeDev(
        Request $request,
        Environment $twig,
        SettingsRepositoryInterface $settingsRepository,
        LoggerInterface $logger,
        Profiler $profiler,
    ): Response {
        $logger->info('A request was made to the frontend controller');
        try {
            $settings = $settingsRepository->getDefaultSettings();
        } catch (TableNotFoundException $exception) {
            $logger->info('Redirected to install page');

            return new RedirectResponse('/install');
        } catch (NoTenantFoundException $e) {
            // Disable the profiler because if we don't we'll
            // just get another tenant exception from the profiler in dev mode.
            $profiler->purge();
            $profiler->disable();
            $logger->warning('No tenant was found', ['hostname' => $request->getHost()]);

            return new Response($twig->render('not_found.html.twig'));
        }

        return new Response($twig->render('index.html.twig'));
    }

    #[Route('/error/stripe', name: 'app_site_error', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null])]
    #[Route('/error/stripe-invalid', name: 'app_site_error_invalid', requirements: ['vueRouting' => '.+'], defaults: ['vueRouting' => null])]
    public function stripeError(
        Environment $twig,
        LoggerInterface $logger,
    ): Response {
        $logger->warning('A user has ended up on the stripe error page');

        return new Response($twig->render('index.html.twig'));
    }
}
