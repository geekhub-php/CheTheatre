Feature: Online booking   
    In order to purchase the tickets in more convenient way
    As a customer
    I want have an opportunity to buy tickets online
    
    Scenario: Buying tickets for the show online (general)
      Given the main page of the website is opened
      When I click on the button 'Purchase tickets'
      Then the page 'Tickets' opens
      When I click here on the button 'Buy tickets online'
      Then the page 'Playbill' opens
      When I select the performance by clicking on it in the calendar
      Then the page of this show opens
      When I click the button 'Book tickets online' in the schedule of forthcoming performances of this play
      Then the page 'Seat Selection' opens
      When I select the available seat by clicking on it
      Then the color of the seat becomes red
      And for other users the color of the seat becomes light grey
      When I click the button 'Add to cart'
      Then the seat I selected appears in the Cart as an item
      And the timer appears counting down 15 minutes
      When I click the button 'Next'
      Then the page 'Order Placing' opens
      And the timer appears counting down 5 minutes
      When I fill the form with the following valid data:
        | Name  | Tony Stark                |
        | email | user.chetheatre@gmail.com |      
      And I click the button 'Pay for the order'
      Then the new page opens in the same browser tab with the message that informs me that the completion of the purchasing will be made on the LiqPay page
      And the new page 'LiqPay Payment' opens in the new browse tab with the form 'Payment Info'
      When I fill the form with the following valid data of my cart with enough funds to pay for the order:
        | Card №          | 1234 5678 9012 3456 |
        | Expiration date | 14/03/2019          |
        | CVV2/CVC2       | 123                 |      
      And I click the button 'Next';
      Then the form where I should confirm my phone number appears
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
