Feature: View webhooks

  Background:
    Given the following accounts exist:
      | Name        | Email                   | Password  |
      | Sally Brown | sally.brown@example.org | AF@k3P@ss |
      | Tim Brown   | tim.brown@example.org   | AF@k3P@ss |
      | Sally Braun | sally.braun@example.org | AF@k3Pass |

  Scenario: Success
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    And the following webhook endpoints exist:
      | Name        | URL                 |
      | Example.org | https://example.org |
      | Example.com | https://example.com |
    When I view the webhook "Example.com"
    Then I should see the webhook url is "https://example.com"

  Scenario: Success
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    And the following webhook endpoints exist:
      | Name        | URL                 |
      | Example.org | https://example.org |
      | Example.com | https://example.com |
    When I view the webhook "Example.org"
    Then I should see the webhook url is "https://example.org"