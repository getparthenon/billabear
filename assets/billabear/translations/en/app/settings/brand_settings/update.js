export const BRAND_SETTINGS_UPDATE_TRANSLATIONS = {
    title: "Update Brand Settings - {name}",
    fields: {
        name: "Name",
        email: "Email Address",
        company_name: "Company Name",
        street_line_one: "Street Line 1",
        street_line_two: "Street Line 2",
        city: "City",
        region: "Region",
        country: "Country",
        postcode: "Post Code",
        code: "Code",
        tax_number: "Tax Number",
        tax_rate: "Tax Rate",
        digital_services_tax_rate: "Digital Services Tax Rate"
    },
    help_info: {
        name: "The name of the brand",
        code: "The code to be used to identify the brand in API calls. This can't be updated.",
        email: "The email to be used when sending emails to brand customer",
        company_name: "The company name for being billing purposes",
        street_line_one: "The first line of the street billing address",
        street_line_two: "The second line of the street billing address",
        city: "The city for the billing address",
        region: "The region/state for the billing address",
        country: "The customer's billing country - ISO 3166-1 alpha-2 country code.",
        postcode: "The post code for the billing address",
        tax_number: "The tax number for the company/brand",
        tax_rate: "The rax rate that is to be used for your home country or when no other tax rate can be found",
        digital_services_tax_rate: "The tax rate that is to be used for your home country or when no other tax rate can be found for digital services"
    },
    general: "General Settings",
    notifications: "Notifications",
    address_title: "Billing Address",
    success_message: "Updated",
    submit_btn: "Update",
    notification: {
        subscription_creation: "Subscription Creation",
        subscription_cancellation: "Subscription Cancellation",
        expiring_card_warning: "Expiring Card Warning",
        expiring_card_warning_day_before: "Expiring Card Warning - Day Before",
        invoice_created: "Invoice Created",
        invoice_overdue: "Invoice Overdue",
        quote_created: "Quote Created",
        trial_ending_warning: "Trial Ending Warning",
        before_charge_warning: "Before Charge Warning",
        payment_failure: "Payment Failure",
        before_charge_warning_options: {
            none: "None",
            all: "All",
            yearly: "Yearly"
        }
    }
};
