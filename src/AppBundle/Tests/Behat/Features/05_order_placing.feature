Feature: The page 'Order Placing'
  In order to confirm my order and leave my contacts for receiving the order
  As a customer
  I want the page 'Order Placing'
  
  Scenario: Going to the page 'Order Placing', then back to 'Seat Selection', then back to 'Order Placing' 
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    And I add it to the cart
    When I click the button 'Next
    Then the page 'Order Placing' opens
    And the selected seat is displayed in my order
    When I click the button 'Change'
    Then the page 'Seat Selection' is opened
    When I add one more item to the cart
    And I click the button 'Next'
    Then the page 'Order Placing' opens
    And two items displayed in my order
    
  Scenario: Filling the form in the page 'Order Placing' with valid data
    Given the page 'Order Placing' is opened
    And the button 'Pay' is inactive
    When I fill the form with the following valid data:
      | Name  | Tony Stark                |
      | email | user.chetheatre@gmail.com |      
    Then the button 'Pay' becomes active
    
    Scenario: Filling the required field of the form with invalid data
    Given the page 'Order Placing' is opened
    And the button 'Pay' is inactive
    When I fill the required field 'Email' with <invalid email>
    Then the error message concerining the necessity to fill the valid email appears
    And the button 'Pay' is still inactive
    Examples:
    | invalid email             |
    | user.chetheatre@gmail.    |
    | @gmail.com                |
    | gmail.com                 |
    | user.chetheatre@          |
    | Tony Stark                |
    | !@#$@gmail.com            |
    | гыукюсруеруфеку"пьфшдюсщь |
    | שלום@gmail.com            |
