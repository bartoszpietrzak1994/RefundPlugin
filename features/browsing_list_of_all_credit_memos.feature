@refunds
Feature: Browsing list of all credit memos
    In order to have official confirmation of some order units refund
    As an Administrator
    I want to have credit memo generated for each order refund action

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "Mr. Meeseeks T-Shirt" priced at "$10.00"
        And the store has a product "Portal Gun" priced at "$20.00"
        And the store allows shipping with "Galaxy Post"
        And the store allows paying with "Space money"
        And there is a customer "rick.sanchez@wubba-lubba-dub-dub.com" that placed an order "#00000022"
        And the customer bought a single "Mr. Meeseeks T-Shirt"
        And the customer bought a single "Portal Gun"
        And the customer chose "Galaxy Post" shipping method to "United States" with "Space money" payment
        And the order "#00000022" is already paid
        And 1st "Mr. Meeseeks T-Shirt" product from order "#00000022" has already been refunded
        And 1st "Portal Gun" product from order "#00000022" has already been refunded
        And I am logged in as an administrator

    @ui
    Scenario: Having all credit memos listed in the system
        When I browse credit memos
        Then there should be 2 credit memos generated
        And 1st credit memo should be generated for order "#00000022" and has total "$10.00"
        And 2nd credit memo should be generated for order "#00000022" and has total "$20.00"