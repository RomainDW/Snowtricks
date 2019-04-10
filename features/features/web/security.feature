Feature: Security

  Scenario: new registration
    Given I am on "/register"
    When I fill in the following:
      | registration_form_username              | Romain         |
      | registration_form_email                 | test@email.com |
      | registration_form_plainPassword_first   | password       |
      | registration_form_plainPassword_second  | password       |
    And I press "Inscription"
    Then I should see "Merci pour votre inscription !"

  Scenario: login
    Given I am on "/login"
    When I fill in the following:
      | email     | user@email.com  |
      | password  | password        |
    And I press "Connexion"
    Then I should see "Vous êtes maintenant connecté !"
    And I should be on "/"