Feature: Hall scheme
  In order to select the seats for the show tickets of which I want to buy
  As a customer
  I want hall scheme in the page 'Seat Selection'
  
  Scenario: Selecting seat
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    Then the color of the seat becomes red
    And for other users the color of the seat becomes light grey
    And the button 'Add to cart' becomes active
     
  Scenario: Unselecting the previously selected seat by the user
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    Then the color of the seat becomes red
    And for other users the color of the seat becomes light grey
    And the button 'Add to cart' becomes active
    When I click on this seat again 
    Then the seat changes to its previous color for all the users
    And the button 'Add to cart' becomes inactive
      
  Scenario: Unselecting the previously selected seat automatically if there's no user activity
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    Then the color of the seat becomes red
    And for other users the color of the seat becomes light grey
    And the button 'Add to cart' becomes active
    When there's no activity from me on the page for 3 minutes
    Then the seat changes to its previous color for all the users
    And the button 'Add to cart' becomes inactive     

  Scenario: Hovering the mouse over the seats
    Given the page 'Seat Selection' is opened
    When I hover the mouse over the particular seat
    Then the cloud that contains info about this seat (№ row, № seat, the price) appears next to it
    When I hover the mouse over another seat
    Then the cloud that contains info about that seat appears
    And the previous cloud disappears
    When I move the mouse out of hall scheme zone
    Then the cloud disappears

  Scenario: Going back to the page of the show when seats selected but not added to the cart
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    Then the color of the seat becomes red
    And for other users the color of the seat becomes light grey
    And the button 'Add to cart' becomes active
    When I click the button 'Back to the page of the show'
    Then the popup with the message is displayed asking me do I really want to leave this page without adding selected items to a cart 
    When I click 'YES'
    Then the page of the show opens
    And the cart disappears
    When I click the button 'Book tickets online' in the schedule of forthcoming performances of this play
    Then the page 'Seat Selection' opens
    And the cart is empty

  Scenario: Refusing to go back to the page of the show when seats selected but not added to the cart
    Given the page 'Seat Selection' is opened
    When I select the available seat on the scheme by clicking on it
    Then the color of the seat becomes red
    And for other users the color of the seat becomes light grey
    And the button 'Add to cart' becomes active
    When I click the button 'Back to the page of the show'
    Then the popup with the message is displayed asking me do I really want to leave this page without adding selected items to a cart 
    When I click 'NO'
    Then the page 'Seat Selection' is still opened
    And the seats I selected are still selected
