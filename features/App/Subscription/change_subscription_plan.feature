Feature: Customer Subscription Update Plan

  Background:
    Given the following accounts exist:
      | Name        | Email                   | Password  |
      | Sally Brown | sally.brown@example.org | AF@k3P@ss |
      | Tim Brown   | tim.brown@example.org   | AF@k3P@ss |
      | Sally Braun | sally.braun@example.org | AF@k3Pass |
    And the follow products exist:
      | Name          | External Reference |
      | Product One   | prod_jf9j545       |
      | Product Two   | prod_jf9j542       |
      | Product Three | prod_jf9jk42       |
    And the follow prices exist:
      | Product     | Amount | Currency | Recurring | Schedule | Public |
      | Product One | 1000   | USD      | true      | week     | true   |
      | Product One | 3000   | USD      | true      | month    | true   |
      | Product One | 3500   | USD      | true      | month    | true   |
      | Product One | 30000  | USD      | true      | year     | false  |
      | Product Two | 4500   | USD      | true      | month    | true   |
      | Product Three | 5500   | USD      | true      | month    | true   |
      | Product Three | 55000   | USD      | true      | year    | true   |
    And the following features exist:
      | Name          | Code          | Description     |
      | Feature One   | feature_one   | A dummy feature |
      | Feature Two   | feature_two   | A dummy feature |
      | Feature Three | feature_three | A dummy feature |
    Given a Subscription Plan exists for product "Product One" with a feature "Feature One" and a limit for "Feature Two" with a limit of 10 and price 3500 in "USD" with:
      | Name       | Test Plan |
      | Public     | True      |
      | Per Seat   | False     |
      | User Count | 10        |
    Given a Subscription Plan exists for product "Product Two" with a feature "Feature One" and a limit for "Feature Two" with a limit of 10 and price 4500 in "USD" with:
      | Name       | Better Test Plan |
      | Public     | True      |
      | Per Seat   | False     |
      | User Count | 10        |
    Given a Subscription Plan exists for product "Product Three" with a feature "Feature One" and a limit for "Feature Two" with a limit of 10 and price 5500 in "USD" with:
      | Name       | Even Better Test Plan |
      | Public     | True      |
      | Per Seat   | False     |
      | User Count | 10        |


  Scenario: Update Plan
    When I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    And the follow customers exist:
      | Email                    | Country | External Reference | Reference    |
      | customer.one@example.org | DE      | cust_jf9j545       | Customer One |
      | customer.two@example.org | UK      | cust_dfugfdu       | Customer Two |
    And the following subscriptions exist:
      | Subscription Plan | Price Amount | Price Currency | Price Schedule | Customer                 |
      | Test Plan         | 3000         | USD            | month          | customer.one@example.org |
    And the following payment details:
      | Customer Email           | Last Four | Expiry Month | Expiry Year |
      | customer.one@example.org | 0444      | 03           | 25          |
    When I update the subscription "Test Plan" for "customer.one@example.org" to plan:
      | Product | Product Two      |
      | Plan    | Better Test Plan |
      | Price   | 4500             |
      | Currency| USD              |
    Then the subscription "Test Plan" for "customer.one@example.org" will not exist
    And the subscription "Better Test Plan" for "customer.one@example.org" will exist

  Scenario: Update Plan - Read
    When I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    And the follow customers exist:
      | Email                    | Country | External Reference | Reference    |
      | customer.one@example.org | DE      | cust_jf9j545       | Customer One |
      | customer.two@example.org | UK      | cust_dfugfdu       | Customer Two |
    And the following subscriptions exist:
      | Subscription Plan | Price Amount | Price Currency | Price Schedule | Customer                 |
      | Test Plan         | 3000         | USD            | month          | customer.one@example.org |
    And the following payment details:
      | Customer Email           | Last Four | Expiry Month | Expiry Year |
      | customer.one@example.org | 0444      | 03           | 25          |
    When I go to update the subscription plan for "Test Plan" for "customer.one@example.org"
    Then I will see the plan "Even Better Test Plan" with the price 5500 in "USD"
    Then I will see the plan "Better Test Plan" with the price 4500 in "USD"
    Then I will not see the plan "Even Better Test Plan" with the price 55000 in "USD"
