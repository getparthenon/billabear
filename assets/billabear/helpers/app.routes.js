import Dashboard from "../views/App/Dashboard.vue";
import UserSettings from "../views/App/User/UserSettings.vue";
import UserInvite from "../views/App/User/UserInvite.vue";
import CustomerList from "../views/App/Customer/CustomerList.vue";
import CustomerCreate from "../views/App/Customer/CustomerCreate.vue";
import CustomerView from "../views/App/Customer/CustomerView.vue";
import CustomerUpdate from "../views/App/Customer/CustomerUpdate.vue";
import ProductList from "../views/App/Product/ProductList.vue";
import ProductUpdate from "../views/App/Product/ProductUpdate.vue";
import ProductView from "../views/App/Product/ProductView.vue";
import ProductCreate from "../views/App/Product/ProductCreate.vue";
import PriceCreate from "../views/App/Price/PriceCreate.vue";
import FeatureList from "../views/App/Feature/FeatureList.vue";
import FeatureCreate from "../views/App/Feature/FeatureCreate.vue";
import SubscriptionPlanCreate from "../views/App/SubscriptionPlan/SubscriptionPlanCreate.vue";
import SubscriptionPlanView from "../views/App/SubscriptionPlan/SubscriptionPlanView.vue";
import SubscriptionPlanUpdate from "../views/App/SubscriptionPlan/SubscriptionPlanUpdate.vue";
import AddPaymentDetails from "../views/App/PaymentDetails/AddPaymentDetails.vue";
import SubscriptionCreate from "../views/App/Subscription/SubscriptionCreate.vue";
import TransactionView from "../views/App/transactions/TransactionView.vue";
import SettingsGroup from "../views/App/Settings/SettingsGroup.vue";
import SubscriptionGroup from "../views/App/Subscription/SubscriptionGroup.vue";
import ProductGroup from "../views/App/Product/ProductGroup.vue";
import CustomerGroup from "../views/App/Customer/CustomerGroup.vue";
import BrandSettingsList from "../views/App/Settings/BrandSettings/BrandSettingsList.vue";
import BrandSettingsUpdate from "../views/App/Settings/BrandSettings/BrandSettingsUpdate.vue";
import BrandSettingsCreate from "../views/App/Settings/BrandSettings/BrandSettingsCreate.vue";
import PdfTemplateList from "../views/App/Settings/PdfTemplates/PdfTemplateList.vue";
import PdfTemplateUpdate from "../views/App/Settings/PdfTemplates/PdfTemplateUpdate.vue";
import EmailTemplateList from "../views/App/Settings/EmailTemplates/EmailTemplateList.vue";
import EmailTemplateCreate from "../views/App/Settings/EmailTemplates/EmailTemplateCreate.vue";
import EmailTemplateUpdate from "../views/App/Settings/EmailTemplates/EmailTemplateUpdate.vue";
import NotificationSettingsUpdate from "../views/App/Settings/NotificationSettings/NotificationSettingsUpdate.vue";
import SystemSettingsUpdate from "../views/App/Settings/SystemSettings/SystemSettingsUpdate.vue";
import TeamUserList from "../views/App/Settings/Team/TeamUserList.vue";
import SettingsUserUpdate from "../views/App/Settings/Team/SettingsUserUpdate.vue";
import StripeImportList from "../views/App/Settings/Stripe/StripeImportList.vue";
import ApiKeysMain from "../views/App/Settings/ApiKeys/ApiKeysMain.vue";
import ReportsGroup from "../views/App/Reports/ReportsGroup.vue";
import CreditCreate from "../views/App/Credit/CreditCreate.vue";
import InvoicesList from "../views/App/Invoices/InvoicesList.vue";
import StripeImportView from "../views/App/Settings/Stripe/StripeImportView.vue";
import UnpaidInvoicesList from "../views/App/Invoices/UnpaidInvoicesList.vue";
import ExchangeRatesList from "../views/App/Settings/ExchangeRates/ExchangeRatesList.vue";
import VouchersList from "../views/App/Vouchers/VouchersList.vue";
import VouchersCreate from "../views/App/Vouchers/VouchersCreate.vue";
import VouchersView from "../views/App/Vouchers/VouchersView.vue";
import TaxSettings from "../views/App/Settings/TaxSettings/TaxSettings.vue";
import InvoiceGroup from "../views/App/Invoices/InvoiceGroup.vue";
import InvoiceCreate from "../views/App/Invoices/InvoiceCreate.vue";
import QuotesList from "../views/App/Quotes/QuotesList.vue";
import QuotesView from "../views/App/Quotes/QuotesView.vue";
import QuoteCreate from "../views/App/Quotes/QuoteCreate.vue";
import InvoicesView from "../views/App/Invoices/InvoicesView.vue";
import {SYSTEM_ROUTES} from "./app.system.routes";
import SystemGroup from "../views/App/System/SystemGroup.vue";
import {AppSubscriptionsRoutes} from "./app.subscriptions.routes";
import {WORKFLOWS_ROUTES} from "./app.workflows.routes";
import WorkflowsGroup from "../views/App/Workflows/WorkflowsGroup.vue";
import PdfGeneratorSettings from "../views/App/Settings/PdfTemplates/PdfGeneratorSettings.vue";
import {AppFinanceRoutes} from "./app.finance.routes";
import {REPORT_ROUTES} from "./app.reports.routes";
import PdfTemplateCreate from "../views/App/Settings/PdfTemplates/PdfTemplateCreate.vue";
import IntegrationsList from "../views/App/System/Integrations/IntegrationsList.vue";
import SlackGroup from "../views/App/System/Integrations/Slack/SlackGroup.vue";
import SlackNotificationList from "../views/App/System/Integrations/Slack/SlackNotificationList.vue";
import SlackNotificationCreate from "../views/App/System/Integrations/Slack/SlackNotificationCreate.vue";
import SlackWebhookList from "../views/App/System/Integrations/Slack/SlackWebhookList.vue";
import SlackWebhookCreate from "../views/App/System/Integrations/Slack/SlackWebhookCreate.vue";
import AddWithToken from "../views/App/PaymentDetails/AddWithToken.vue";

// All paths have the prefix /app/.
export const APP_ROUTES = [
    {
        name: "app.home",
        path: "home",
        component: Dashboard,
    },
    {
        name: "app.reports",
        path: "reports",
        redirect: {name: "app.report.subscriptions"},
        component: ReportsGroup,
        children: REPORT_ROUTES,
    },
    {
        name: "app.system",
        path: "system",
        component: SystemGroup,
        children: SYSTEM_ROUTES
    },
    {
        name: "app.workflows",
        path: "workflows",
        redirect: {name: 'app.workflows.cancellation_request.list'},
        component: WorkflowsGroup,
        children: WORKFLOWS_ROUTES
    },
    {
        name: 'app.customer',
        path: 'customers',
        redirect: "customers",
        component: CustomerGroup,
        children: [
            {
                name: 'app.customer.list',
                path: '',
                component: CustomerList
            },
            {
                name: 'app.customer.create',
                path: 'customer/create',
                component: CustomerCreate,
            },
            {
                name: 'app.customer.view',
                path: 'customer/view/:id',
                component: CustomerView
            },
            {
                name: 'app.customer.update',
                path: 'customer/update/:id',
                component: CustomerUpdate
            },
            {
                name: 'app.customer.payment_details.add',
                path: 'customer/:customerId/payment-details/add',
                component: AddPaymentDetails
            },
            {
                name: 'app.customer.payment_details.token',
                path: 'customer/:customerId/payment-details/token',
                component: AddWithToken
            },
            {
                name: 'app.subscription.create',
                path: 'customer/:customerId/subscription/add',
                component: SubscriptionCreate
            },
            {
                name: 'app.customer.credit.add',
                path: 'customer/:customerId/credit/add',
                component: CreditCreate
            }
        ]
    },

    {
        name: 'app.user.settings',
        path: "user",
        component: UserSettings,
    },
    {
        name: 'app.settings',
        path: 'settings',
        component: SettingsGroup,
        redirect: { name: "app.settings.pdf_template.list", },
        children: [
            {
                name: "app.user.invite",
                path: "users/invite",
                component: UserInvite,
            },
            {
                name: "app.settings.pdf_template.list",
                path: "templates/list",
                component: PdfTemplateList
            },
            {
                name: "app.settings.tax_settings.update",
                path: "tax-settings",
                component: TaxSettings
            },
            {
                name: "app.settings.pdf_template.create",
                path: "templates/create",
                component: PdfTemplateCreate
            },
            {
                name: "app.settings.pdf_template.update",
                path: "templates/update/:id",
                component: PdfTemplateUpdate
            },
            {
                name: "app.settings.pdf_template.generator",
                path: "templates/generator",
                component: PdfGeneratorSettings
            },
            {
                name: "app.settings.brand_settings.list",
                path: "brand-settings",
                component: BrandSettingsList
            },
            {
                name: "app.settings.brand_settings.update",
                path: "brand-settings/:id",
                component: BrandSettingsUpdate
            },
            {
                name: "app.settings.brand_settings.create",
                path: "brand-settings/new",
                component: BrandSettingsCreate
            },
            {
                name: "app.settings.email_template.list",
                path: "email-template/list",
                component: EmailTemplateList
            },
            {
                name: "app.settings.email_template.create",
                path: "email-template/create",
                component: EmailTemplateCreate
            },
            {
                name: "app.settings.email_template.update",
                path: "email-template/:id/update",
                component: EmailTemplateUpdate
            },
            {
                name: "app.settings.notification_settings.update",
                path: "notification-settings/update",
                component: NotificationSettingsUpdate
            },
            {
                name: "app.settings.system_settings.update",
                path: "system/update",
                component: SystemSettingsUpdate
            },
            {
                name: "app.settings.users.list",
                path: "users/list",
                component: TeamUserList
            },
            {
                name: "app.settings.users.update",
                path: "users/:id/update",
                component: SettingsUserUpdate
            },
            {
                name: "app.settings.import.stripe",
                path: "import/stripe",
                component: StripeImportList,
            },
            {
                name: "app.settings.import.stripe.view",
                path: "import/stripe/:id/view",
                component: StripeImportView,
            },
            {
                name: "app.settings.api_keys.main",
                path: "api-keys",
                component: ApiKeysMain,
            },
            {
                name: "app.settings.exchange_rates.list",
                path: "exchange-rates",
                component: ExchangeRatesList
            },
            {
                name: 'app.settings.integrations.list',
                path: 'integrations/list',
                component: IntegrationsList
            },
            {
                name: 'app.settings.integrations.slack',
                path: 'integrations/slack',
                redirect: "webhook",
                component: SlackGroup,
                children: [
                    {
                        name: 'app.system.integrations.slack.notification',
                        path: 'notification',
                        component: SlackNotificationList
                    },
                    {
                        name: 'app.system.integrations.slack.notification.create',
                        path: 'notification/create',
                        component: SlackNotificationCreate

                    },
                    {
                        name: 'app.system.integrations.slack.webhook',
                        path: 'webhook',
                        component: SlackWebhookList
                    },
                    {
                        name: 'app.system.integrations.slack.webhook.create',
                        path: 'webhook/create',
                        component: SlackWebhookCreate

                    }
                ]
            }
        ]
    },
    {
        name: 'app.product',
        path: 'product',
        redirect: "product",
        component: ProductGroup,
        children: [
            {
                name: 'app.price.create',
                path: 'product/:productId/price/create',
                component: PriceCreate,
            },
            {
                name: 'app.subscription_plan.create',
                path: 'product/:productId/subscription-plan/create',
                component: SubscriptionPlanCreate,
            },
            {
                name: 'app.subscription_plan.view',
                path: 'product/:productId/subscription-plan/view/:subscriptionPlanId',
                component: SubscriptionPlanView,
            },
            {
                name: 'app.subscription_plan.update',
                path: 'product/:productId/subscription-plan/update/:subscriptionPlanId',
                component: SubscriptionPlanUpdate,
            },
            {
                name: 'app.feature.list',
                path: 'feature/list',
                component: FeatureList
            },
            {
                name: 'app.feature.create',
                path: 'feature/create',
                component: FeatureCreate
            },
            {
                name: 'app.product.list',
                path: 'list',
                component: ProductList
            },
            {
                name: 'app.product.create',
                path: 'product/create',
                component: ProductCreate,
            },
            {
                name: 'app.product.view',
                path: 'product/view/:id',
                component: ProductView
            },
            {
                name: 'app.product.update',
                path: 'product/update/:id',
                component: ProductUpdate
            },
            {
                name: 'app.vouchers.list',
                path: 'vouchers/list',
                component: VouchersList
            },
            {
                name: 'app.vouchers.create',
                path: 'vouchers/create',
                component: VouchersCreate
            },
            {
                name: 'app.vouchers.view',
                path: 'vouchers/view/:id',
                component: VouchersView
            },
        ]
    },
    {
        name: 'app.subscription',
        path: 'subscriptions',
        redirect: { name: "app.subscription.list", },
        component: SubscriptionGroup,
        children: AppSubscriptionsRoutes
    },
    {
        name: "app.invoices",
        path: "invoices",
        component: InvoiceGroup,
        children: [
            {
                name: 'app.invoices.list',
                path: "invoices/list",
                component: InvoicesList
            },
            {
                name: 'app.invoices.unpaid_list',
                path: "invoices/unpaid/list",
                component: UnpaidInvoicesList
            },
            {
                name: 'app.invoices.create',
                path: "invoices/create",
                component: InvoiceCreate,
            },
            {
                name: 'app.invoices.view',
                path: "invoices/view/:id",
                component: InvoicesView,
            },
            {
                name: 'app.quotes.create',
                path: "quotes/create",
                component: QuoteCreate,
            },
            {
                name: 'app.quotes.list',
                path: "quotes",
                component: QuotesList,
            },
            {
                name: 'app.quotes.view',
                path: "quotes/view/:id",
                component: QuotesView,
            }
        ]
    },
    {
        name: 'app.finance',
        path: 'finance',
        redirect: "finance",
        component: TransactionView,
        children: AppFinanceRoutes,
    }
]
