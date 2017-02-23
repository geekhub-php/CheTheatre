Feature: Timer in the cart
  In order to protect the system of misuse, to give the user limited precise time for managing the order
  As a ticket seller
  I want the timer 15 minutes
  
  Scenario: Starting the timer
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    And I add it to the cart
    Then the timer in the cart starts to count down 15 minutes
    
  Scenario: Staying with the item in the cart until the time's up
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    And I add it to the cart
    Then the timer in the cart starts to count down 15 minutes
    And I click the button 'Back to the playbill'
    And the timer in the cart keeps counting down
    And I am waiting untill the time is ou
    When the time is up
    Then the popup appears with the corresponding message 
    And all items in the cart are canceled automatically
    And the cart is empty
    
  Scenario: Going to the page 'Order Placing' and back
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    And I add it to the cart
    Then the timer in the cart starts to count down 15 minutes
    When I click the button 'Next
    Then the page 'Order Placing' opens
    And the timer appears here and keeps counting down
    When I click the button 'Back'
    Then the page 'Seat Selection' openes
    And the timer keeps counting down in the cart
