Feature: LiqPay integration
  In order to receive money from ticket selling in my bank account
  As a ticket seller
  I want LiqPay the payment system integration
  
  Scenario: Going to the LiqPay page
    Given the page 'Order Placing' is opened
    And there the order of one ticket with total sum to pay of 80 hrn
    When I fill the form with the following valid data:
      | Name  | Tony Stark                |
      | email | user.chetheatre@gmail.com |      
    And I click the button 'Pay for the order'
    Then the new page opens in the same browser tab with the message that informs me that the completion of the purchasing will be made on the LiqPay page
    And the new page 'LiqPay Payment' with the order info I need to confirm opens in the new browser tab
    
  Scenario: Subscribing the form with valid card data
    Given the new page 'LiqPay Payment' with the order info I need to confirm by clicking the button 'Pay' is opened in the new browser tab
    When I click the button 'Pay' 
    Then the page with the form 'Payment Info' opens
    When I fill the form with the following valid data of my cart with enough funds to pay for the order:
      | Card №          | 1234 5678 9012 3456 |
      | Expiration date | 14/03/2019          |
      | CVV2/CVC2       | 123                 |      
    And I click the button 'Next'
    Then the form where I should confirm my phone number appears
    
  Scenario: Confirmation of the payment via phone number
    Given the form where I should confirm my phone number is displayed
    When I fill the form with the following valid data (active phone number connected to the cart designated in the previous step):
      | Phone Number | +38066 600 06 66 |      
    And I click the button 'Next'
    Then the system sends the 8-digit code on my phone number
    And the form where I should enter this number opens
    When I fill the form with the code
    And click the button 'Pay'
    Then the page message appears that informs me about the successful finish, sum of the transfer and its ID
    And the sum of money that equals to the price of the order is transferred from my card to the theatre's account
    And the system sends me email with my ticket
    And the seat on the scheme becomes dark grey

  Scenario: Staying in the page of payment for 2 hours without payment
# if I am not mistaking, 2 hours is the given time by LiqPay to fill the forms and make a payment and this time cannot be customized
    Given the page with the form 'Payment Info' is opened
    When 2 hours are passed
    Then the seats that were selected for this order become available for selection
    When I fill the form with the following valid data of my cart with enough funds to pay for the order:
      | Card №          | 1234 5678 9012 3456 |
      | Expiration date | 14/03/2019          |
      | CVV2/CVC2       | 123                 |      
    And I click the button 'Next'
    Then the pop-up appears with the message concerning the cancelation of the order because the time was out
  
  Scenario: Leaving the payment page without payment
    Given the page with the form 'Payment Info' is opened
    When I close the page
    Then the seats that were selected for this order become available for selection
